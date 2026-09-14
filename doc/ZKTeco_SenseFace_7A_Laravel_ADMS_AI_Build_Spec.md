# ZKTeco SenseFace 7A → Laravel ADMS Push Integration
## AI Coding Agent Build Specification

> **Purpose:** Build a production-ready Laravel application that receives attendance data from ZKTeco SenseFace 7A face-recognition terminals using the ZKTeco ADMS Push protocol, stores raw and normalized attendance data in MySQL, processes records through Redis queues, and exposes application APIs for downstream HR/ERP integration.

---

## 1. Project Objective

Build a Laravel-based ZKTeco attendance gateway with these capabilities:

- Receive SenseFace 7A device registration/heartbeat requests.
- Receive real-time attendance pushes through ADMS.
- Parse ZKTeco tab-separated attendance payloads.
- Store every raw request for audit/troubleshooting.
- Store normalized attendance records in MySQL.
- Prevent duplicate attendance records.
- Maintain device status and last-seen timestamps.
- Support multiple SenseFace 7A devices and locations.
- Map ZKTeco device PINs to application employees.
- Process attendance asynchronously with Redis queues.
- Support future device commands through ADMS polling.
- Provide REST APIs for devices, attendance, mappings, and monitoring.
- Provide secure HTTPS deployment.
- Include automated tests and Postman/cURL test examples.

---

# 2. Technology Stack

Use:

- PHP 8.3+ or the PHP version required by the selected Laravel release
- Laravel
- MySQL 8+
- Redis
- Laravel Queue
- Blade/AdminLTE for administration if an admin UI is required
- REST API
- HTTPS
- Git

Recommended architecture:

```text
SenseFace 7A
      |
      | HTTPS / ADMS Push
      v
Laravel ADMS Gateway
      |
      +---- Raw Request Logger
      |
      +---- Attendance Parser
      |
      +---- Device Registry
      |
      +---- Attendance Repository
      |
      +---- Redis Queue
                  |
                  v
        Attendance Processor
                  |
          +-------+-------+
          |       |       |
       Employee  Shift   Location
          |
          v
      HR/ERP API
```

---

# 3. Public ADMS URLs

Assume the application domain is:

```text
https://attendance.example.com
```

The primary URLs are:

```text
GET  https://attendance.example.com/iclock/cdata
POST https://attendance.example.com/iclock/cdata
GET  https://attendance.example.com/iclock/getrequest
POST https://attendance.example.com/iclock/devicecmd
```

Do not expose Laravel authentication or normal user-session middleware on these routes.

The SenseFace 7A should normally be configured with:

```text
Server Address: attendance.example.com
Server Port: 443
Protocol: HTTPS
```

The device/firmware normally constructs the `/iclock/...` paths.

---

# 4. ADMS Communication Flow

## 4.1 Device registration / handshake

The terminal connects to:

```http
GET /iclock/cdata?SN=DEVICE_SERIAL&options=all&pushver=...
```

Laravel must:

1. Read `SN`.
2. Find or register the device.
3. Update the device IP.
4. Update `last_seen_at`.
5. Save the raw request.
6. Return an ADMS-compatible plain-text response.

Example response:

```text
GET OPTION FROM: DEVICE_SERIAL
Stamp=0
OpStamp=0
ErrorDelay=60
Delay=30
TransTimes=00:00;14:05
TransInterval=1
TransFlag=1111000000
TimeZone=+06:00
Realtime=1
```

---

## 4.2 Attendance push

The terminal sends:

```http
POST /iclock/cdata?SN=DEVICE_SERIAL&table=ATTLOG
Content-Type: text/plain
```

Example body:

```text
10001	2026-09-14 08:31:15	0	15	0	0	0
10002	2026-09-14 08:32:22	0	15	0	0	0
```

Typical fields:

```text
PIN
DateTime
Status
Verify
WorkCode
Reserved
Reserved
```

The exact field set and verification-code meanings can vary by firmware. Preserve the original raw payload.

Laravel should return quickly:

```text
OK: 2
```

Do not perform heavy HR/payroll processing before returning the response.

---

# 5. Laravel Project Structure

Use a clean service-oriented structure:

```text
app/
├── Console/
│   └── Commands/
│       └── CheckZkDevices.php
│
├── Http/
│   └── Controllers/
│       └── ZkTeco/
│           ├── AdmsController.php
│           ├── DeviceController.php
│           └── AttendanceController.php
│
├── Jobs/
│   └── ZkTeco/
│       └── ProcessAttendance.php
│
├── Models/
│   ├── ZkDevice.php
│   ├── ZkAttendanceLog.php
│   ├── ZkRawRequest.php
│   ├── ZkDeviceCommand.php
│   └── ZkEmployeeMapping.php
│
└── Services/
    └── ZkTeco/
        ├── AdmsService.php
        ├── AttendanceParser.php
        ├── DeviceService.php
        └── CommandService.php

routes/
├── web.php
├── api.php
└── zkteco.php
```

Prefer a dedicated `routes/zkteco.php` route file if the Laravel version/application structure supports it.

---

# 6. Environment Configuration

`.env`:

```env
APP_NAME="ZKTeco Attendance Gateway"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://attendance.example.com
APP_TIMEZONE=Asia/Dhaka

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zkteco_attendance
DB_USERNAME=zkteco
DB_PASSWORD=CHANGE_ME

QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=null
REDIS_DB=0

ZKTECO_PUSH_SECRET=CHANGE_ME_TO_A_LONG_RANDOM_VALUE
ZKTECO_ALLOW_AUTO_REGISTER=false
ZKTECO_DEVICE_OFFLINE_MINUTES=5
```

Configure:

```php
'timezone' => env('APP_TIMEZONE', 'Asia/Dhaka'),
```

---

# 7. Database Design

Create these tables:

```text
zk_devices
zk_attendance_logs
zk_raw_requests
zk_device_commands
zk_employee_mappings
```

Optional future tables:

```text
zk_device_heartbeats
zk_attendance_processing_logs
zk_device_events
```

---

# 8. `zk_devices`

Fields:

```text
id BIGINT PK
serial_number VARCHAR(100) UNIQUE NOT NULL
device_name VARCHAR(255) NULL
device_ip VARCHAR(45) NULL
model VARCHAR(100) NULL
firmware_version VARCHAR(100) NULL
push_version VARCHAR(100) NULL
site_code VARCHAR(100) NULL
location VARCHAR(255) NULL
is_active BOOLEAN DEFAULT TRUE
last_seen_at TIMESTAMP NULL
metadata JSON NULL
created_at
updated_at
```

Indexes:

```text
UNIQUE(serial_number)
INDEX(device_ip)
INDEX(last_seen_at)
INDEX(site_code)
```

Migration:

```php
Schema::create('zk_devices', function (Blueprint $table) {
    $table->id();
    $table->string('serial_number', 100)->unique();
    $table->string('device_name')->nullable();
    $table->string('device_ip', 45)->nullable();
    $table->string('model')->nullable();
    $table->string('firmware_version')->nullable();
    $table->string('push_version')->nullable();
    $table->string('site_code')->nullable();
    $table->string('location')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamp('last_seen_at')->nullable();
    $table->json('metadata')->nullable();
    $table->timestamps();

    $table->index('device_ip');
    $table->index('last_seen_at');
    $table->index('site_code');
});
```

---

# 9. `zk_attendance_logs`

Migration:

```php
Schema::create('zk_attendance_logs', function (Blueprint $table) {
    $table->id();

    $table->foreignId('device_id')
        ->nullable()
        ->constrained('zk_devices')
        ->nullOnDelete();

    $table->string('serial_number', 100);
    $table->string('employee_pin', 50);
    $table->dateTime('attendance_time');

    $table->unsignedInteger('status')->nullable();
    $table->unsignedInteger('verify_type')->nullable();
    $table->unsignedInteger('work_code')->nullable();

    $table->string('reserved_1')->nullable();
    $table->string('reserved_2')->nullable();

    $table->text('raw_data')->nullable();
    $table->string('source', 50)->default('zkteco_push');

    $table->timestamps();

    $table->index(['serial_number', 'employee_pin', 'attendance_time']);
    $table->index('attendance_time');
    $table->index('employee_pin');
});
```

After confirming the exact record uniqueness characteristics of the SenseFace firmware, add an appropriate unique constraint. Do not assume that PIN + timestamp is always sufficient if the device can generate multiple legitimate records with identical timestamps.

---

# 10. `zk_raw_requests`

Every device request should be retained.

Migration:

```php
Schema::create('zk_raw_requests', function (Blueprint $table) {
    $table->id();

    $table->string('serial_number', 100)->nullable();
    $table->string('method', 10);
    $table->string('uri', 500);

    $table->json('query_params')->nullable();
    $table->longText('headers')->nullable();
    $table->longText('body')->nullable();

    $table->string('remote_ip', 45)->nullable();
    $table->timestamp('received_at');

    $table->timestamps();

    $table->index('serial_number');
    $table->index('received_at');
});
```

Purpose:

- Troubleshooting
- Protocol compatibility
- Firmware differences
- Failed parsing investigation
- Audit trail

---

# 11. `zk_device_commands`

Migration:

```php
Schema::create('zk_device_commands', function (Blueprint $table) {
    $table->id();

    $table->foreignId('device_id')
        ->constrained('zk_devices')
        ->cascadeOnDelete();

    $table->string('command_id')->unique();
    $table->text('command');

    $table->enum('status', [
        'pending',
        'sent',
        'completed',
        'failed'
    ])->default('pending');

    $table->text('response')->nullable();
    $table->timestamp('sent_at')->nullable();
    $table->timestamp('completed_at')->nullable();

    $table->timestamps();

    $table->index(['device_id', 'status']);
});
```

---

# 12. `zk_employee_mappings`

Never assume the ZKTeco PIN is the application's employee ID.

Migration:

```php
Schema::create('zk_employee_mappings', function (Blueprint $table) {
    $table->id();

    $table->foreignId('device_id')
        ->constrained('zk_devices')
        ->cascadeOnDelete();

    $table->string('device_pin', 50);
    $table->string('employee_code', 100);

    $table->boolean('is_active')->default(true);

    $table->timestamps();

    $table->unique(['device_id', 'device_pin']);
    $table->index('employee_code');
});
```

Example:

```text
Device: SF7A001
PIN: 10025
Employee: EMP-00452
```

---

# 13. Models

## ZkDevice

```php
class ZkDevice extends Model
{
    protected $fillable = [
        'serial_number',
        'device_name',
        'device_ip',
        'model',
        'firmware_version',
        'push_version',
        'site_code',
        'location',
        'is_active',
        'last_seen_at',
        'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_seen_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function attendanceLogs()
    {
        return $this->hasMany(ZkAttendanceLog::class, 'device_id');
    }

    public function employeeMappings()
    {
        return $this->hasMany(ZkEmployeeMapping::class, 'device_id');
    }
}
```

## ZkAttendanceLog

```php
class ZkAttendanceLog extends Model
{
    protected $fillable = [
        'device_id',
        'serial_number',
        'employee_pin',
        'attendance_time',
        'status',
        'verify_type',
        'work_code',
        'reserved_1',
        'reserved_2',
        'raw_data',
        'source',
    ];

    protected $casts = [
        'attendance_time' => 'datetime',
    ];

    public function device()
    {
        return $this->belongsTo(ZkDevice::class);
    }
}
```

---

# 14. ADMS Routes

Recommended routes:

```php
use App\Http\Controllers\ZkTeco\AdmsController;

Route::get('/iclock/cdata', [
    AdmsController::class,
    'handshake'
]);

Route::post('/iclock/cdata', [
    AdmsController::class,
    'attendance'
]);

Route::get('/iclock/getrequest', [
    AdmsController::class,
    'getRequest'
]);

Route::post('/iclock/devicecmd', [
    AdmsController::class,
    'deviceCommand'
]);
```

Expected external URLs:

```text
GET  /iclock/cdata
POST /iclock/cdata
GET  /iclock/getrequest
POST /iclock/devicecmd
```

If using `routes/api.php` with Laravel's `/api` prefix, the URLs become `/api/iclock/...`. Avoid this unless the device is configured for that path.

---

# 15. CSRF

The ADMS endpoints are machine-to-machine endpoints.

Exclude:

```text
iclock/*
```

from Laravel CSRF verification if they are served through a CSRF-protected middleware group.

Do not put these routes behind normal web authentication.

---

# 16. ADMS Controller

Create:

```text
app/Http/Controllers/ZkTeco/AdmsController.php
```

Implement:

```php
namespace App\Http\Controllers\ZkTeco;

use App\Http\Controllers\Controller;
use App\Models\ZkDevice;
use App\Models\ZkRawRequest;
use App\Services\ZkTeco\AttendanceParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdmsController extends Controller
{
    public function handshake(Request $request)
    {
        $serial = $request->query('SN');

        if (!$serial) {
            return response('ERROR: SN required', 400);
        }

        $this->storeRawRequest($request, $serial);

        $device = ZkDevice::firstOrCreate(
            ['serial_number' => $serial],
            [
                'device_ip' => $request->ip(),
                'is_active' => true,
            ]
        );

        $device->update([
            'device_ip' => $request->ip(),
            'push_version' => $request->query('pushver'),
            'last_seen_at' => now(),
            'metadata' => $request->query(),
        ]);

        return response(
            "GET OPTION FROM: {$serial}\r\n" .
            "Stamp=0\r\n" .
            "OpStamp=0\r\n" .
            "ErrorDelay=60\r\n" .
            "Delay=30\r\n" .
            "TransTimes=00:00;14:05\r\n" .
            "TransInterval=1\r\n" .
            "TransFlag=1111000000\r\n" .
            "TimeZone=+06:00\r\n" .
            "Realtime=1\r\n",
            200
        )->header('Content-Type', 'text/plain');
    }

    public function attendance(
        Request $request,
        AttendanceParser $parser
    ) {
        $serial = $request->query('SN');

        if (!$serial) {
            return response('ERROR: SN required', 400);
        }

        $this->storeRawRequest($request, $serial);

        $device = ZkDevice::where(
            'serial_number',
            $serial
        )->where(
            'is_active',
            true
        )->first();

        if (!$device) {
            return response('ERROR: Unknown Device', 403);
        }

        $device->update([
            'device_ip' => $request->ip(),
            'last_seen_at' => now(),
        ]);

        $table = strtoupper(
            $request->query('table', 'ATTLOG')
        );

        if ($table !== 'ATTLOG') {
            return response('OK', 200)
                ->header('Content-Type', 'text/plain');
        }

        $count = $parser->parseAndStore(
            $device,
            $request->getContent()
        );

        return response(
            "OK: {$count}",
            200
        )->header('Content-Type', 'text/plain');
    }

    public function getRequest(Request $request)
    {
        $serial = $request->query('SN');

        // Implement command polling through CommandService.
        // Return an empty/appropriate ADMS response when no command exists.

        return response('OK', 200)
            ->header('Content-Type', 'text/plain');
    }

    public function deviceCommand(Request $request)
    {
        $serial = $request->query('SN');

        $this->storeRawRequest($request, $serial);

        Log::info('ZKTeco device command response', [
            'serial' => $serial,
            'body' => $request->getContent(),
        ]);

        return response('OK', 200)
            ->header('Content-Type', 'text/plain');
    }

    private function storeRawRequest(
        Request $request,
        ?string $serial
    ): void {
        ZkRawRequest::create([
            'serial_number' => $serial,
            'method' => $request->method(),
            'uri' => $request->getRequestUri(),
            'query_params' => $request->query(),
            'headers' => json_encode(
                $request->headers->all()
            ),
            'body' => $request->getContent(),
            'remote_ip' => $request->ip(),
            'received_at' => now(),
        ]);
    }
}
```

Refactor this controller further into services before final production deployment.

---

# 17. Attendance Parser Service

Create:

```text
app/Services/ZkTeco/AttendanceParser.php
```

Requirements:

1. Accept raw text.
2. Split by CRLF/LF.
3. Split each row by tabs.
4. Validate minimum required fields.
5. Parse date/time using `Asia/Dhaka`.
6. Preserve raw row.
7. Detect duplicates.
8. Create attendance records.
9. Dispatch asynchronous processing jobs.
10. Never lose malformed rows; log them.

Example implementation:

```php
namespace App\Services\ZkTeco;

use App\Jobs\ZkTeco\ProcessAttendance;
use App\Models\ZkAttendanceLog;
use App\Models\ZkDevice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceParser
{
    public function parseAndStore(
        ZkDevice $device,
        string $body
    ): int {
        $lines = preg_split(
            "/\r\n|\n|\r/",
            trim($body)
        );

        $count = 0;

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $fields = preg_split(
                "/\t+/",
                $line
            );

            if (count($fields) < 2) {
                Log::warning(
                    'Malformed ZKTeco attendance row',
                    [
                        'serial' => $device->serial_number,
                        'line' => $line,
                    ]
                );

                continue;
            }

            $pin = trim($fields[0]);
            $dateTime = trim($fields[1]);

            try {
                $attendanceTime = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $dateTime,
                    config('app.timezone')
                );
            } catch (\Throwable $e) {
                Log::warning(
                    'Invalid ZKTeco attendance timestamp',
                    [
                        'serial' => $device->serial_number,
                        'line' => $line,
                    ]
                );

                continue;
            }

            $record = ZkAttendanceLog::firstOrCreate(
                [
                    'serial_number' => $device->serial_number,
                    'employee_pin' => $pin,
                    'attendance_time' => $attendanceTime,
                ],
                [
                    'device_id' => $device->id,
                    'status' => isset($fields[2])
                        ? (int) $fields[2]
                        : null,
                    'verify_type' => isset($fields[3])
                        ? (int) $fields[3]
                        : null,
                    'work_code' => isset($fields[4])
                        ? (int) $fields[4]
                        : null,
                    'reserved_1' => $fields[5] ?? null,
                    'reserved_2' => $fields[6] ?? null,
                    'raw_data' => $line,
                    'source' => 'zkteco_push',
                ]
            );

            if ($record->wasRecentlyCreated) {
                ProcessAttendance::dispatch(
                    $record->id
                );
                $count++;
            }
        }

        return $count;
    }
}
```

For high-volume deployments, replace per-record `firstOrCreate` with optimized bulk insert/upsert logic.

---

# 18. Queue Job

Create:

```bash
php artisan make:job ProcessAttendance
```

Job responsibilities:

```text
Attendance ID
     |
     v
Find attendance record
     |
     v
Find device employee mapping
     |
     v
Find application employee
     |
     v
Calculate attendance status
     |
     v
Update HR/ERP attendance
     |
     v
Log processing result
```

Do not make the ADMS HTTP request wait for this work.

Recommended queue configuration:

```env
QUEUE_CONNECTION=redis
```

Worker:

```bash
php artisan queue:work redis --tries=3 --max-time=3600
```

---

# 19. Device Monitoring

Every handshake and attendance push must update:

```text
last_seen_at
device_ip
```

A device is considered offline when:

```text
last_seen_at < now() - configured offline threshold
```

Default:

```env
ZKTECO_DEVICE_OFFLINE_MINUTES=5
```

Create:

```bash
php artisan make:command CheckZkDevices
```

The command should:

- Find active devices.
- Compare `last_seen_at`.
- Mark/report offline devices.
- Optionally send email/notification.
- Avoid sending repeated alerts for the same outage.

Schedule it every minute.

---

# 20. Device Administration

Create an admin interface for:

- Device list
- Add device
- Edit device
- Activate/deactivate
- Device serial number
- Device model
- Firmware
- Site
- Location
- Last seen
- Current status
- IP address
- Employee mappings
- Raw request viewer
- Attendance history
- Command history

Dashboard example:

```text
ZKTeco Devices

ONLINE    12
OFFLINE    2
TOTAL     14
```

Device table:

```text
Serial     Site       IP              Status    Last Seen
SF7A001    Lungla     192.168.x.x     Online    12:38
SF7A002    Etah       192.168.x.x     Online    12:37
SF7A003    Estate 3   192.168.x.x     Offline   10:02
```

---

# 21. Security Requirements

Implement:

## HTTPS

Production endpoint:

```text
https://attendance.example.com/iclock/cdata
```

Do not use plain HTTP in production.

## Device validation

Validate `SN`.

Unknown devices should be rejected unless controlled auto-registration is explicitly enabled.

## Communication key

If the SenseFace firmware exposes an ADMS communication key, configure and validate it according to the exact firmware/protocol behavior.

## Rate limiting

Use a dedicated rate limit for unexpected traffic, but ensure legitimate terminals cannot be throttled during bursts.

## IP logging

Record the source IP.

Do not rely solely on IP because estate/ISP public addresses may change.

## Raw request privacy

Raw requests may contain employee identifiers. Restrict administrative access.

## Secrets

Never hard-code communication secrets in source code.

---

# 22. Postman Testing

## Handshake

Method:

```text
GET
```

URL:

```text
https://attendance.example.com/iclock/cdata?SN=TEST123&options=all&pushver=3.1.2&language=83
```

Expected:

```text
GET OPTION FROM: TEST123
Stamp=0
OpStamp=0
ErrorDelay=60
Delay=30
TransTimes=00:00;14:05
TransInterval=1
TransFlag=1111000000
TimeZone=+06:00
Realtime=1
```

## Attendance

Method:

```text
POST
```

URL:

```text
https://attendance.example.com/iclock/cdata?SN=TEST123&table=ATTLOG
```

Header:

```text
Content-Type: text/plain
```

Body:

```text
10001	2026-09-14 08:30:00	0	15	0	0	0
10002	2026-09-14 08:35:00	0	15	0	0	0
```

Expected:

```text
OK: 2
```

Verify:

```sql
SELECT *
FROM zk_attendance_logs
ORDER BY id DESC;
```

---

# 23. cURL Testing

Handshake:

```bash
curl "https://attendance.example.com/iclock/cdata?SN=TEST123&options=all&pushver=3.1.2"
```

Attendance:

```bash
curl -X POST \
  "https://attendance.example.com/iclock/cdata?SN=TEST123&table=ATTLOG" \
  -H "Content-Type: text/plain" \
  --data-binary $'10001\t2026-09-14 08:30:00\t0\t15\t0\t0\t0\n10002\t2026-09-14 08:35:00\t0\t15\t0\t0\t0'
```

---

# 24. SenseFace 7A Configuration

On the terminal, locate the ADMS/Cloud Server/Push communication settings. Exact menu names can vary by firmware.

Configure approximately:

```text
ADMS / Push: Enabled

Server Mode:
Domain

Server Address:
attendance.example.com

Server Port:
443

Protocol:
HTTPS
```

Do not normally enter:

```text
https://attendance.example.com/iclock/cdata
```

as the server address if the firmware expects only the hostname.

Use:

```text
attendance.example.com
```

The terminal should construct the `/iclock/...` endpoint.

---

# 25. Network Architecture

The terminal does not need a public IP.

Recommended:

```text
SenseFace 7A
Private LAN IP
       |
       | outbound HTTPS
       v
Estate Firewall
       |
       v
Internet
       |
       v
Public DNS
       |
       v
Laravel Server
```

Only the Laravel application needs a public HTTPS endpoint.

---

# 26. Multi-Site Architecture

For multiple tea estates:

```text
SenseFace 7A #001
       |
SenseFace 7A #002
       |
SenseFace 7A #003
       |
       v
https://attendance.example.com/iclock/cdata
       |
       v
Laravel
       |
       +-- Device Registry
       |
       +-- Site Mapping
       |
       +-- Employee Mapping
       |
       +-- Attendance
```

Each request includes the device serial number.

Example:

```text
SN=SF7A001
SN=SF7A002
SN=SF7A003
```

Map each serial number to a site/estate.

---

# 27. Verification Type

Do not hard-code face/fingerprint mappings until they are confirmed for the exact SenseFace 7A firmware.

Store the raw numeric value:

```text
verify_type
```

Create a configurable mapping table/service later.

Example application-level mapping:

```php
return match ($type) {
    1 => 'Fingerprint',
    15 => 'Face',
    0 => 'Password',
    default => 'Unknown',
};
```

Treat these values as configurable because device/firmware implementations may differ.

---

# 28. Idempotency

Attendance pushes may be retransmitted.

Requirements:

- Save raw request every time.
- Avoid creating duplicate normalized attendance.
- Use a deterministic uniqueness strategy.
- Prefer database-level protection over application-only duplicate checks.
- Preserve protocol-specific record identifiers/stamps if available from the device.

Never rely only on:

```php
if (!$exists) {
    insert();
}
```

because concurrent requests can still create duplicates.

Use a database unique key or transactional upsert strategy after confirming the exact uniqueness fields.

---

# 29. Error Handling

The ADMS endpoint must be resilient.

For malformed records:

- Do not crash the entire request.
- Log the malformed row.
- Preserve the raw request.
- Continue processing valid rows.
- Return an appropriate ADMS response.

For database failures:

- Log the exception.
- Do not silently discard the payload.
- Preserve the raw payload whenever possible.
- Use retryable processing for asynchronous work.

---

# 30. Observability

Create:

```text
ZKTeco Dashboard
```

Metrics:

```text
Total devices
Online devices
Offline devices
Attendance received today
Attendance processed today
Failed attendance
Malformed payloads
Last request time
Last attendance time
Queue backlog
```

Logs:

```text
Device handshake
Attendance push
Parser errors
Unknown device
Duplicate attendance
Queue failure
Command result
```

---

# 31. Application APIs

Keep ADMS endpoints separate from application REST APIs.

Suggested API:

```text
GET    /api/v1/zkteco/devices
POST   /api/v1/zkteco/devices
GET    /api/v1/zkteco/devices/{device}
PUT    /api/v1/zkteco/devices/{device}
DELETE /api/v1/zkteco/devices/{device}

GET    /api/v1/zkteco/attendance
GET    /api/v1/zkteco/attendance/{attendance}

GET    /api/v1/zkteco/devices/{device}/mappings
POST   /api/v1/zkteco/devices/{device}/mappings
PUT    /api/v1/zkteco/mappings/{mapping}
DELETE /api/v1/zkteco/mappings/{mapping}
```

Protect these APIs using Laravel authentication such as Sanctum if appropriate.

Do not apply this authentication to the ADMS machine endpoints unless the device protocol supports the required mechanism.

---

# 32. Testing Strategy

Create automated tests for:

## Handshake

- Valid SN
- Missing SN
- New device
- Existing device
- Inactive device
- Query parameter persistence

## Attendance

- One record
- Multiple records
- Empty body
- Malformed row
- Invalid timestamp
- Unknown device
- Duplicate record
- Mixed valid/invalid records

## Security

- Unknown device
- Invalid communication key where implemented
- Rate limit behavior
- Unauthorized admin access

## Queue

- Job dispatched
- Retry
- Failure handling

## Device monitoring

- Online device
- Offline device
- Missing last_seen_at

---

# 33. Deployment

Production requirements:

```text
PHP
MySQL
Redis
Composer
Nginx/Apache
SSL certificate
Queue worker
Cron/Scheduler
```

Laravel deployment:

```bash
composer install --no-dev --optimize-autoloader

php artisan migrate --force

php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Queue:

```bash
php artisan queue:work redis --tries=3 --max-time=3600
```

Use Supervisor/systemd or the hosting platform's process manager for persistent workers.

If using cPanel and a persistent queue worker is not available, use the hosting provider's supported cron/queue strategy. Do not assume a single `queue:work` command invoked once will remain running.

---

# 34. Scheduler

Schedule device monitoring:

```php
Schedule::command('zkteco:check-devices')
    ->everyMinute();
```

Also schedule cleanup/retention tasks if required:

```text
Raw request retention: configurable
Application attendance retention: long-term
Log retention: configurable
```

Do not delete raw data before the required audit/troubleshooting retention period.

---

# 35. AI Coding Agent Development Rules

The AI coding agent must:

1. Inspect the existing Laravel application before modifying it.
2. Detect Laravel version.
3. Detect PHP version.
4. Inspect existing database structure.
5. Reuse existing authentication/layout/components where appropriate.
6. Never overwrite unrelated application code.
7. Create migrations rather than manually modifying production tables.
8. Create Form Requests where validation is needed.
9. Use service classes for ADMS logic.
10. Use database transactions for multi-table writes.
11. Use queue jobs for heavy processing.
12. Add automated tests.
13. Run migrations/tests after each phase.
14. Report every changed file.
15. Never hard-code credentials.
16. Never expose raw employee/device data unnecessarily.
17. Preserve raw ADMS payloads.
18. Keep ADMS protocol handling separate from business attendance logic.
19. Make device/firmware-specific parsing configurable.
20. Produce production-ready error handling.

---

# 36. Phase-by-Phase AI Coding Prompts

## Phase 1 — Inspect Existing Project

AI command/prompt:

```text
You are a senior Laravel architect.

Inspect this Laravel project before changing anything.

Determine:
- Laravel version
- PHP version
- database driver
- queue configuration
- Redis availability
- route architecture
- authentication architecture
- existing employee/user tables
- existing site/location tables
- existing attendance tables
- existing AdminLTE/Blade layout
- testing framework

Do not modify code.

Create a technical assessment describing:
1. Existing architecture
2. Tables that can be reused
3. Tables that must be added
4. Potential conflicts
5. Recommended integration approach

Do not invent existing tables or fields.
```

---

## Phase 2 — Database

```text
Implement the ZKTeco integration database layer.

Create migrations/models for:
- zk_devices
- zk_attendance_logs
- zk_raw_requests
- zk_device_commands
- zk_employee_mappings

Requirements:
- foreign keys
- indexes
- appropriate data types
- timestamps
- JSON metadata
- unique constraints for safe idempotency
- soft deletion only where appropriate

Run migrations and automated tests.

Do not modify unrelated tables.
```

---

## Phase 3 — ADMS Endpoints

```text
Implement the ZKTeco ADMS HTTP endpoints.

Create:

GET  /iclock/cdata
POST /iclock/cdata
GET  /iclock/getrequest
POST /iclock/devicecmd

Implement:
- SN extraction
- device registration
- last_seen_at update
- source IP capture
- raw request logging
- plain-text ADMS responses
- unknown-device handling

Exclude these endpoints from normal user authentication and CSRF where required.

Add feature tests for all endpoints.
```

---

## Phase 4 — Attendance Parser

```text
Implement a dedicated ZKTeco AttendanceParser service.

Input:
tab-separated ADMS ATTLOG text.

Requirements:
- CRLF/LF support
- multiple records
- malformed-row handling
- timestamp parsing
- timezone Asia/Dhaka
- status
- verification type
- work code
- reserved fields
- raw row preservation
- duplicate prevention
- database transaction where appropriate

Do not hard-code firmware-specific assumptions without documenting them.

Create unit tests for valid, invalid, empty, and duplicate payloads.
```

---

## Phase 5 — Queue Processing

```text
Implement asynchronous ZKTeco attendance processing.

Create:
ProcessAttendance job.

Flow:
attendance record
-> employee mapping
-> application employee
-> business attendance processing
-> ERP/HR integration point

Requirements:
- Redis queue
- retry
- backoff
- failed-job handling
- logging
- idempotency

The ADMS HTTP request must not wait for heavy processing.
```

---

## Phase 6 — Device Management

```text
Build the ZKTeco device management module.

Features:
- device list
- create
- edit
- activate/deactivate
- serial number
- model
- firmware
- site
- location
- IP
- last seen
- online/offline status

Use existing application UI/layout where available.

Add validation and authorization.
```

---

## Phase 7 — Employee Mapping

```text
Build ZKTeco employee mapping.

Allow an administrator to map:

device
+
ZKTeco PIN
+
application employee code

Requirements:
- unique device/PIN
- active/inactive
- validation
- search
- import-ready design
- audit logging if the application already supports it
```

---

## Phase 8 — Monitoring

```text
Implement ZKTeco device monitoring.

Create:
zkteco:check-devices

Requirements:
- configurable offline threshold
- online/offline detection
- last_seen_at
- device dashboard metrics
- optional notification integration
- no duplicate outage notifications

Add scheduler configuration and tests.
```

---

## Phase 9 — REST API

```text
Create authenticated REST APIs under:

/api/v1/zkteco/

Implement device, attendance, and employee-mapping endpoints.

Use:
- API Resources
- Form Requests
- authentication
- authorization
- pagination
- filtering
- sorting
- consistent JSON responses

Do not expose ADMS endpoints through the authenticated API namespace.
```

---

## Phase 10 — Security Hardening

```text
Perform a security review of the ZKTeco ADMS integration.

Check:
- HTTPS
- CSRF handling
- authentication boundaries
- unknown devices
- communication key support
- rate limiting
- input validation
- SQL injection
- mass assignment
- raw payload access
- sensitive logging
- authorization
- secret management
- replay/duplicate handling

Fix all identified issues.

Do not break legitimate ADMS communication.
```

---

## Phase 11 — Testing

```text
Create a complete automated test suite for the ZKTeco integration.

Cover:
- handshake
- attendance push
- multiple records
- malformed records
- duplicate records
- unknown devices
- inactive devices
- raw request storage
- queue dispatch
- employee mapping
- command polling
- device monitoring
- API authorization

Run the full test suite and fix failures.
```

---

## Phase 12 — Production Readiness

```text
Perform a production-readiness review.

Verify:
- migrations
- indexes
- queue
- Redis
- scheduler
- logging
- HTTPS
- environment variables
- backups
- retention
- monitoring
- error handling
- database performance
- concurrency
- idempotency
- deployment documentation

Create:
DEPLOYMENT.md
TROUBLESHOOTING.md
POSTMAN.md
ZKTECO-ADMS.md

Do not mark the project production-ready until all critical issues are addressed.
```

---

# 37. Final Acceptance Criteria

The project is complete only when all of these work:

```text
[ ] SenseFace 7A can reach Laravel over HTTPS
[ ] GET /iclock/cdata succeeds
[ ] Device serial is stored
[ ] Device last_seen_at updates
[ ] POST /iclock/cdata succeeds
[ ] Attendance payload is parsed
[ ] Multiple attendance records work
[ ] Raw request is stored
[ ] Duplicate records are prevented
[ ] Employee mapping works
[ ] Queue job is dispatched
[ ] Queue processing works
[ ] Device online/offline status works
[ ] /iclock/getrequest works
[ ] /iclock/devicecmd works
[ ] Admin device management works
[ ] REST APIs work
[ ] Authentication/authorization works
[ ] HTTPS is enforced
[ ] Secrets are environment-based
[ ] Automated tests pass
[ ] Production deployment is documented
```

---

# 38. Final Expected Architecture

```text
                    ┌──────────────────────┐
                    │  SenseFace 7A #001   │
                    └──────────┬───────────┘
                               │
                    ┌──────────▼───────────┐
                    │  SenseFace 7A #002   │
                    └──────────┬───────────┘
                               │
                    ┌──────────▼───────────┐
                    │  SenseFace 7A #003   │
                    └──────────┬───────────┘
                               │
                               │ HTTPS / ADMS
                               ▼
                 ┌─────────────────────────────┐
                 │ Laravel ZKTeco ADMS Gateway │
                 ├─────────────────────────────┤
                 │ /iclock/cdata               │
                 │ /iclock/getrequest           │
                 │ /iclock/devicecmd            │
                 └──────────────┬──────────────┘
                                │
             ┌──────────────────┼──────────────────┐
             │                  │                  │
             ▼                  ▼                  ▼
       Raw Requests       Device Registry     Attendance
             │                  │                  │
             │                  │                  ▼
             │                  │             MySQL
             │                  │                  │
             │                  └──────────────────┤
             │                                     │
             │                                     ▼
             │                              Redis Queue
             │                                     │
             │                                     ▼
             │                              ProcessAttendance
             │                                     │
             │                         ┌───────────┼───────────┐
             │                         ▼           ▼           ▼
             │                     Employee      Shift       Site
             │                         │           │           │
             │                         └───────────┼───────────┘
             │                                     ▼
             │                                HR / ERP
             │
             ▼
       Audit / Troubleshooting
```

---

# 39. Recommended Implementation Principle

Treat ZKTeco ADMS as a **device-integration boundary**, not as the application's attendance business logic.

The separation should always be:

```text
ZKTeco Protocol
       ↓
ADMS Gateway
       ↓
Raw Payload
       ↓
Normalized Attendance
       ↓
Employee Mapping
       ↓
Business Attendance
       ↓
HR / ERP
```

This makes the application easier to maintain, test, scale, and adapt if another ZKTeco model or another biometric vendor is introduced later.
