# Hikvision DS-K1T320EFWX → Laravel Push/Webhook Integration Guide

## 1. Overview

This guide implements a Hikvision MinMoe **DS-K1T320EFWX** attendance integration using the **Push/Webhook method** with a Laravel application.

Recommended architecture:

```text
DS-K1T320EFWX
       │
       │ HTTPS POST
       ▼
https://yourdomain.com/api/hikvision/events
       │
       ▼
HikvisionEventController
       │
       ▼
Raw Event Storage
       │
       ▼
Queue / Processor
       │
       ▼
Attendance Logs
       │
       ▼
HR/Attendance System
```

The DS-K1T320 series supports ISAPI and Hikvision documents HTTP Listening/event notification configuration for an event alarm IP/domain, URL, port and protocol.

---

# 2. Final Webhook URL

Suppose the Laravel application is:

```text
https://attendance.example.com
```

The recommended webhook endpoint is:

```text
POST https://attendance.example.com/api/hikvision/events
```

If the application is:

```text
https://hrm.duncanbd.net
```

the webhook URL is:

```text
POST https://hrm.duncanbd.net/api/hikvision/events
```

The Hikvision terminal sends events **to Laravel**:

```text
Hikvision → Laravel
```

The application does not need to expose the Hikvision device to the internet.

---

# 3. Production Architecture

```text
             ┌─────────────────────┐
             │ DS-K1T320EFWX       │
             │ MinMoe Terminal     │
             └──────────┬──────────┘
                        │
                    HTTPS POST
                        │
                        ▼
             ┌─────────────────────┐
             │ Laravel API         │
             │ /api/hikvision/     │
             │ events              │
             └──────────┬──────────┘
                        │
                 Store raw event
                        │
                        ▼
             ┌─────────────────────┐
             │ hikvision_events    │
             │ Queue               │
             └──────────┬──────────┘
                        │
                    Queue Job
                        │
                        ▼
             ┌─────────────────────┐
             │ attendance_logs     │
             └──────────┬──────────┘
                        │
                        ▼
             ┌─────────────────────┐
             │ HR / Attendance     │
             │ Application         │
             └─────────────────────┘
```

The webhook should remain lightweight. Save the incoming event, return HTTP 200 quickly, and perform attendance processing asynchronously.

# 5. Database Design

Use three core tables:

```text
hikvision_devices
hikvision_events
attendance_logs
```

The raw `hikvision_events` table is especially important because it preserves the original device payload for debugging and future parser improvements.

---

# 6. Hikvision Devices Migration

Create:

```bash
php artisan make:migration create_hikvision_devices_table
```

Migration:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hikvision_devices', function (Blueprint $table) {

            $table->id();

            $table->string('device_name', 100);

            $table->string('device_serial', 100)
                ->nullable()
                ->unique();

            $table->string('device_model', 100)
                ->nullable();

            $table->string('ip_address', 45)
                ->nullable();

            $table->string('location', 150)
                ->nullable();

            $table->string('username', 100)
                ->nullable();

            $table->text('password_encrypted')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->dateTime('last_event_at')
                ->nullable();

            $table->timestamps();

            $table->index('is_active');
            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hikvision_devices');
    }
};
```

---

# 7. Hikvision Events Migration

Create:

```bash
php artisan make:migration create_hikvision_events_table
```

Migration:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hikvision_events', function (Blueprint $table) {

            $table->id();

            $table->foreignId('device_id')
                ->nullable()
                ->constrained('hikvision_devices')
                ->nullOnDelete();

            $table->string('event_type', 100)
                ->nullable();

            $table->string('event_state', 50)
                ->nullable();

            $table->dateTime('event_time')
                ->nullable();

            $table->string('employee_no', 100)
                ->nullable();

            $table->string('employee_name', 150)
                ->nullable();

            $table->string('card_no', 100)
                ->nullable();

            $table->integer('major_event_type')
                ->nullable();

            $table->integer('sub_event_type')
                ->nullable();

            $table->string('attendance_status', 50)
                ->nullable();

            $table->string('verify_mode', 50)
                ->nullable();

            $table->bigInteger('serial_no')
                ->nullable();

            $table->longText('raw_payload');

            $table->enum('payload_format', [
                'XML',
                'JSON',
                'UNKNOWN'
            ])->default('UNKNOWN');

            $table->enum('processing_status', [
                'PENDING',
                'PROCESSED',
                'FAILED',
                'IGNORED'
            ])->default('PENDING');

            $table->text('processing_message')
                ->nullable();

            $table->dateTime('received_at');

            $table->dateTime('processed_at')
                ->nullable();

            $table->timestamps();

            $table->index('employee_no');
            $table->index('event_time');
            $table->index('processing_status');
            $table->index('serial_no');

            $table->unique([
                'device_id',
                'serial_no'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hikvision_events');
    }
};
```

---

# 8. Attendance Logs Migration

If the existing application already has an attendance table, adapt this migration to that schema.

Create:

```bash
php artisan make:migration create_attendance_logs_table
```

Migration:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_logs', function (Blueprint $table) {

            $table->id();

            $table->string('employee_no', 100);

            $table->foreignId('device_id')
                ->nullable()
                ->constrained('hikvision_devices')
                ->nullOnDelete();

            $table->dateTime('attendance_time');

            $table->string('attendance_type', 30)
                ->nullable();

            $table->string('verify_mode', 50)
                ->nullable();

            $table->string('source', 30)
                ->default('HIKVISION');

            $table->foreignId('hikvision_event_id')
                ->nullable()
                ->unique()
                ->constrained('hikvision_events')
                ->nullOnDelete();

            $table->timestamps();

            $table->index([
                'employee_no',
                'attendance_time'
            ]);

            $table->index([
                'device_id',
                'attendance_time'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
```

Run:

```bash
php artisan migrate
```

---

# 9. Create Eloquent Models

```bash
php artisan make:model HikvisionDevice
php artisan make:model HikvisionEvent
php artisan make:model AttendanceLog
```

## 9.1 HikvisionDevice

`app/Models/HikvisionDevice.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HikvisionDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_name',
        'device_serial',
        'device_model',
        'ip_address',
        'location',
        'username',
        'password_encrypted',
        'is_active',
        'last_event_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_event_at' => 'datetime',
    ];

    public function events()
    {
        return $this->hasMany(
            HikvisionEvent::class,
            'device_id'
        );
    }
}
```

## 9.2 HikvisionEvent

`app/Models/HikvisionEvent.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HikvisionEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'event_type',
        'event_state',
        'event_time',
        'employee_no',
        'employee_name',
        'card_no',
        'major_event_type',
        'sub_event_type',
        'attendance_status',
        'verify_mode',
        'serial_no',
        'raw_payload',
        'payload_format',
        'processing_status',
        'processing_message',
        'received_at',
        'processed_at',
    ];

    protected $casts = [
        'event_time' => 'datetime',
        'received_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public function device()
    {
        return $this->belongsTo(
            HikvisionDevice::class,
            'device_id'
        );
    }

    public function attendance()
    {
        return $this->hasOne(
            AttendanceLog::class,
            'hikvision_event_id'
        );
    }
}
```

## 9.3 AttendanceLog

`app/Models/AttendanceLog.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_no',
        'device_id',
        'attendance_time',
        'attendance_type',
        'verify_mode',
        'source',
        'hikvision_event_id',
    ];

    protected $casts = [
        'attendance_time' => 'datetime',
    ];

    public function device()
    {
        return $this->belongsTo(
            HikvisionDevice::class,
            'device_id'
        );
    }

    public function hikvisionEvent()
    {
        return $this->belongsTo(
            HikvisionEvent::class,
            'hikvision_event_id'
        );
    }
}
```

---

# 10. Create Hikvision Event Parser

Create:

```text
app/Services/HikvisionEventParser.php
```

Code:

```php
<?php

namespace App\Services;

use Carbon\Carbon;
use SimpleXMLElement;
use RuntimeException;

class HikvisionEventParser
{
    public function parse(
        string $payload,
        ?string $contentType = null
    ): array {

        $format = $this->detectFormat(
            $payload,
            $contentType
        );

        if ($format === 'JSON') {

            $data = json_decode(
                $payload,
                true
            );

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException(
                    'Invalid Hikvision JSON payload.'
                );
            }

            return [
                'format' => 'JSON',
                'data' => $this->normalize($data),
            ];
        }

        if ($format === 'XML') {

            libxml_use_internal_errors(true);

            $xml = simplexml_load_string(
                $payload
            );

            if (!$xml instanceof SimpleXMLElement) {
                throw new RuntimeException(
                    'Invalid Hikvision XML payload.'
                );
            }

            $data = json_decode(
                json_encode($xml),
                true
            );

            return [
                'format' => 'XML',
                'data' => $this->normalize($data),
            ];
        }

        throw new RuntimeException(
            'Unknown Hikvision payload format.'
        );
    }

    protected function detectFormat(
        string $payload,
        ?string $contentType
    ): string {

        $contentType =
            strtolower($contentType ?? '');

        if (
            str_contains($contentType, 'json')
        ) {
            return 'JSON';
        }

        if (
            str_contains($contentType, 'xml')
        ) {
            return 'XML';
        }

        $trimmed = ltrim($payload);

        if (
            str_starts_with($trimmed, '<')
        ) {
            return 'XML';
        }

        if (
            str_starts_with($trimmed, '{') ||
            str_starts_with($trimmed, '[')
        ) {
            return 'JSON';
        }

        return 'UNKNOWN';
    }

    protected function normalize(
        array $data
    ): array {

        if (
            isset($data['EventNotificationAlert'])
        ) {
            $data =
                $data['EventNotificationAlert'];
        }

        $access = [];

        if (
            isset($data['AccessControllerEvent'])
        ) {
            $access =
                $data['AccessControllerEvent'];
        }

        return [

            'event_type' =>
                $this->value(
                    $data,
                    'eventType'
                ),

            'event_state' =>
                $this->value(
                    $data,
                    'eventState'
                ),

            'event_time' =>
                $this->date(
                    $this->value(
                        $data,
                        'dateTime'
                    )
                ),

            'employee_no' =>
                $this->value(
                    $access,
                    'employeeNoString'
                ),

            'employee_name' =>
                $this->value(
                    $access,
                    'name'
                ),

            'card_no' =>
                $this->value(
                    $access,
                    'cardNo'
                ),

            'major_event_type' =>
                $this->integer(
                    $access,
                    'majorEventType'
                ),

            'sub_event_type' =>
                $this->integer(
                    $access,
                    'subEventType'
                ),

            'attendance_status' =>
                $this->value(
                    $access,
                    'attendanceStatus'
                ),

            'verify_mode' =>
                $this->value(
                    $access,
                    'currentVerifyMode'
                ),

            'serial_no' =>
                $this->integer(
                    $access,
                    'serialNo'
                ),
        ];
    }

    protected function value(
        array $data,
        string $key
    ) {

        if (!array_key_exists($key, $data)) {
            return null;
        }

        if (is_array($data[$key])) {
            return null;
        }

        return trim(
            (string) $data[$key]
        );
    }

    protected function integer(
        array $data,
        string $key
    ) {

        $value =
            $this->value(
                $data,
                $key
            );

        return $value === null
            ? null
            : (int) $value;
    }

    protected function date($value)
    {
        if (!$value) {
            return null;
        }

        try {

            return Carbon::parse(
                $value
            )->timezone(
                config('app.timezone')
            );

        } catch (\Throwable $e) {

            return null;
        }
    }
}
```

---

# 11. Create Webhook Controller

Create:

```bash
php artisan make:controller Api/HikvisionEventController
```

File:

```text
app/Http/Controllers/Api/HikvisionEventController.php
```

Code:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HikvisionDevice;
use App\Models\HikvisionEvent;
use App\Services\HikvisionEventParser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class HikvisionEventController extends Controller
{
    public function receive(
        Request $request,
        HikvisionEventParser $parser
    ) {

        $rawPayload =
            $request->getContent();

        if (empty($rawPayload)) {

            return response(
                'Empty request body',
                Response::HTTP_BAD_REQUEST
            );
        }

        Log::channel('daily')->info(
            'Hikvision event received',
            [
                'ip' =>
                    $request->ip(),

                'content_type' =>
                    $request->header(
                        'Content-Type'
                    ),

                'payload' =>
                    $rawPayload,
            ]
        );

        try {

            $result =
                $parser->parse(
                    $rawPayload,
                    $request->header(
                        'Content-Type'
                    )
                );

            $data =
                $result['data'];

            $device =
                $this->findDevice(
                    $request,
                    $data
                );

            /*
             * Prevent duplicate raw events.
             */
            if (
                $device &&
                $data['serial_no'] !== null
            ) {

                $existing =
                    HikvisionEvent::query()
                        ->where(
                            'device_id',
                            $device->id
                        )
                        ->where(
                            'serial_no',
                            $data['serial_no']
                        )
                        ->first();

                if ($existing) {
                    return response('OK', 200);
                }
            }

            $event =
                HikvisionEvent::create([
                    'device_id' =>
                        $device?->id,

                    'event_type' =>
                        $data['event_type'],

                    'event_state' =>
                        $data['event_state'],

                    'event_time' =>
                        $data['event_time'],

                    'employee_no' =>
                        $data['employee_no'],

                    'employee_name' =>
                        $data['employee_name'],

                    'card_no' =>
                        $data['card_no'],

                    'major_event_type' =>
                        $data['major_event_type'],

                    'sub_event_type' =>
                        $data['sub_event_type'],

                    'attendance_status' =>
                        $data['attendance_status'],

                    'verify_mode' =>
                        $data['verify_mode'],

                    'serial_no' =>
                        $data['serial_no'],

                    'raw_payload' =>
                        $rawPayload,

                    'payload_format' =>
                        $result['format'],

                    'processing_status' =>
                        'PENDING',

                    'received_at' =>
                        now(),
                ]);

            if ($device) {

                $device->update([
                    'last_event_at' => now(),
                ]);
            }

            /*
             * Queue processing can be added here:
             *
             * ProcessHikvisionEvent::dispatch($event->id);
             */

            return response(
                'OK',
                Response::HTTP_OK
            );

        } catch (\Throwable $e) {

            Log::error(
                'Hikvision webhook error',
                [
                    'message' =>
                        $e->getMessage(),

                    'payload' =>
                        $rawPayload,
                ]
            );

            return response(
                'Internal Server Error',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    protected function findDevice(
        Request $request,
        array $data
    ): ?HikvisionDevice {

        return HikvisionDevice::query()
            ->where(
                'ip_address',
                $request->ip()
            )
            ->where(
                'is_active',
                true
            )
            ->first();
    }
}
```

---

# 12. Register the API Route

Open:

```text
routes/api.php
```

Add:

```php
use App\Http\Controllers\Api\HikvisionEventController;

Route::post(
    '/hikvision/events',
    [HikvisionEventController::class, 'receive']
);
```

The final URL becomes:

```text
https://attendance.example.com/api/hikvision/events
```

Because this route is in `api.php`, Laravel's normal web CSRF middleware does not apply.

Do not place this device webhook in `web.php`.

---

# 13. Test the Webhook Using Postman

You can test the Laravel integration before connecting the physical Hikvision terminal.

## 13.1 Request

Postman:

```text
Method:
POST

URL:
https://attendance.example.com/api/hikvision/events
```

Header:

```text
Content-Type: application/xml
```

Body:

**Body → raw → XML**

```xml
<EventNotificationAlert>

    <ipAddress>192.168.1.50</ipAddress>

    <dateTime>
        2026-09-15T08:10:00+06:00
    </dateTime>

    <eventType>
        AccessControllerEvent
    </eventType>

    <eventState>
        active
    </eventState>

    <AccessControllerEvent>

        <employeeNoString>
            EMP001
        </employeeNoString>

        <name>
            Test Employee
        </name>

        <cardNo>
        </cardNo>

        <majorEventType>
            5
        </majorEventType>

        <subEventType>
            75
        </subEventType>

        <attendanceStatus>
            checkIn
        </attendanceStatus>

        <currentVerifyMode>
            face
        </currentVerifyMode>

        <serialNo>
            100001
        </serialNo>

    </AccessControllerEvent>

</EventNotificationAlert>
```

Click **Send**.

Expected response:

```text
HTTP 200 OK

OK
```

---

# 14. Check Laravel Log

Check:

```text
storage/logs/laravel.log
```

You should find:

```text
Hikvision event received
```

and the XML payload.

---

# 15. Check Database

Run:

```sql
SELECT
    id,
    employee_no,
    employee_name,
    event_type,
    event_time,
    attendance_status,
    verify_mode,
    serial_no,
    payload_format,
    processing_status,
    received_at
FROM hikvision_events
ORDER BY id DESC
LIMIT 10;
```

Expected:

```text
EMP001
Test Employee
AccessControllerEvent
2026-09-15 08:10:00
checkIn
face
100001
XML
PENDING
```

At this point:

```text
Postman
   ↓
HTTPS
   ↓
Laravel
   ↓
MySQL
```

is working.

---

# 16. Test JSON

Change header:

```text
Content-Type: application/json
```

Body:

```json
{
    "EventNotificationAlert": {
        "ipAddress": "192.168.1.50",
        "dateTime": "2026-09-15T08:15:00+06:00",
        "eventType": "AccessControllerEvent",
        "eventState": "active",
        "AccessControllerEvent": {
            "employeeNoString": "EMP001",
            "name": "Test Employee",
            "cardNo": "",
            "majorEventType": 5,
            "subEventType": 75,
            "attendanceStatus": "checkIn",
            "currentVerifyMode": "face",
            "serialNo": 100002
        }
    }
}
```

Expected:

```text
HTTP 200 OK
```

---

# 17. Test Check-Out

Send:

```json
{
    "EventNotificationAlert": {
        "ipAddress": "192.168.1.50",
        "dateTime": "2026-09-15T18:05:00+06:00",
        "eventType": "AccessControllerEvent",
        "eventState": "active",
        "AccessControllerEvent": {
            "employeeNoString": "EMP001",
            "name": "Test Employee",
            "majorEventType": 5,
            "subEventType": 75,
            "attendanceStatus": "checkOut",
            "currentVerifyMode": "face",
            "serialNo": 100003
        }
    }
}
```

Expected attendance events:

```text
08:15:00   EMP001   checkIn
18:05:00   EMP001   checkOut
```

---

# 18. Test Duplicate Protection

Send exactly the same event twice:

```text
serialNo = 100010
```

The second request should not create another attendance transaction.

The design uses:

```text
device_id + serial_no
```

for raw-event duplicate detection and:

```text
hikvision_event_id UNIQUE
```

for attendance-level duplicate protection.

This protects against terminal/network retries.

---

# 19. Add Queue Processing

For production, do not perform complicated attendance processing inside the webhook request.

Use:

```text
Hikvision
    ↓
Webhook
    ↓
Save Event
    ↓
Queue Job
    ↓
HTTP 200
    ↓
Attendance Processing
```

Create:

```bash
php artisan make:job ProcessHikvisionEvent
```

---

# 20. ProcessHikvisionEvent Job

File:

```text
app/Jobs/ProcessHikvisionEvent.php
```

Code:

```php
<?php

namespace App\Jobs;

use App\Models\AttendanceLog;
use App\Models\HikvisionEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class ProcessHikvisionEvent implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $eventId
    ) {
    }

    public function handle(): void
    {
        $event =
            HikvisionEvent::find(
                $this->eventId
            );

        if (!$event) {
            return;
        }

        if (
            $event->processing_status ===
            'PROCESSED'
        ) {
            return;
        }

        if (!$event->employee_no) {

            $event->update([
                'processing_status' =>
                    'IGNORED',

                'processing_message' =>
                    'Employee number missing',

                'processed_at' =>
                    now(),
            ]);

            return;
        }

        try {

            DB::transaction(
                function () use ($event) {

                    AttendanceLog::create([

                        'employee_no' =>
                            $event->employee_no,

                        'device_id' =>
                            $event->device_id,

                        'attendance_time' =>
                            $event->event_time,

                        'attendance_type' =>
                            $event->attendance_status,

                        'verify_mode' =>
                            $event->verify_mode,

                        'source' =>
                            'HIKVISION',

                        'hikvision_event_id' =>
                            $event->id,
                    ]);

                    $event->update([

                        'processing_status' =>
                            'PROCESSED',

                        'processed_at' =>
                            now(),

                        'processing_message' =>
                            null,
                    ]);
                }
            );

        } catch (\Throwable $e) {

            $event->update([

                'processing_status' =>
                    'FAILED',

                'processing_message' =>
                    $e->getMessage(),

                'processed_at' =>
                    now(),
            ]);

            throw $e;
        }
    }
}
```

---

# 21. Dispatch the Queue Job

In the controller, add:

```php
use App\Jobs\ProcessHikvisionEvent;
```

After creating the event:

```php
ProcessHikvisionEvent::dispatch(
    $event->id
);
```

The flow becomes:

```text
Receive event
      ↓
Save event
      ↓
Dispatch job
      ↓
Return HTTP 200
      ↓
Queue worker
      ↓
Create attendance log
```

---

# 22. Configure Redis Queue

In `.env`:

```env
QUEUE_CONNECTION=redis
```

Run a worker:

```bash
php artisan queue:work redis
```

Production example:

```bash
php artisan queue:work redis \
    --sleep=1 \
    --tries=3 \
    --timeout=60
```

Use Supervisor or the hosting/server process manager to keep the worker running in production.

---

# 23. Test Queue Processing

Send a Postman event.

Immediately after receiving:

```sql
SELECT *
FROM hikvision_events
ORDER BY id DESC
LIMIT 1;
```

It may initially show:

```text
processing_status = PENDING
```

After the queue worker processes it:

```text
processing_status = PROCESSED
```

Then:

```sql
SELECT *
FROM attendance_logs
ORDER BY id DESC
LIMIT 1;
```

Expected:

```text
EMP001
2026-09-15 08:15:00
checkIn
face
HIKVISION
```

---

# 24. Configure the DS-K1T320EFWX

Once the Laravel webhook works with Postman, configure the actual terminal.

Login to the terminal web interface:

```text
http://DEVICE-IP
```

Example:

```text
http://192.168.1.50
```

Look for the HTTP Listening / Event Notification configuration.

Depending on firmware, the menu wording may vary.

The DS-K1T320 series documentation describes HTTP Listening/event notification configuration including the event alarm destination, URL, port and protocol.

Recommended values:

```text
Event Alarm IP/Domain:
attendance.example.com

Port:
443

Protocol:
HTTPS

URL:
/api/hikvision/events
```

If the device asks for a complete URL:

```text
https://attendance.example.com/api/hikvision/events
```

---

# 25. Network Requirements

The Hikvision terminal must be able to reach the Laravel server.

Example:

```text
DS-K1T320EFWX
192.168.10.50
       │
       │ Internet
       ▼
Estate Firewall
       │
       ▼
attendance.example.com
       │
       │ TCP 443
       ▼
Laravel Server
```

You do not need to expose the Hikvision device's port 80 to the public internet.

---

# 26. Test Server Connectivity

From another computer on the same network as the terminal:

```bash
curl -I https://attendance.example.com
```

A valid HTTP response confirms that the server is reachable.

---

# 27. Test Using cURL

You can also simulate a device event from a terminal:

```bash
curl -i \
-X POST \
-H "Content-Type: application/xml" \
--data '<EventNotificationAlert><eventType>AccessControllerEvent</eventType><AccessControllerEvent><employeeNoString>EMP001</employeeNoString><name>Test Employee</name><attendanceStatus>checkIn</attendanceStatus><currentVerifyMode>face</currentVerifyMode><serialNo>200001</serialNo></AccessControllerEvent></EventNotificationAlert>' \
https://attendance.example.com/api/hikvision/events
```

Expected:

```text
HTTP/2 200

OK
```

---

# 28. Capture the Real DS-K1T320EFWX Payload

Before finalizing the parser, temporarily log every incoming payload.

The controller already logs the payload to:

```text
storage/logs/laravel.log
```

When the actual terminal performs a face recognition event, inspect the exact payload.

This is important because Hikvision payload structures and available fields can vary with firmware/configuration.

The production workflow should therefore be:

```text
Postman test payload
       ↓
Laravel webhook
       ↓
Connect actual DS-K1T320EFWX
       ↓
Capture real payload
       ↓
Compare with parser
       ↓
Adjust parser if necessary
       ↓
Enable production attendance processing
```

---

# 29. Attendance Processing Design

Do not rely only on `attendanceStatus` for all business rules.

Preserve the original event first:

```text
Hikvision Event
      ↓
Raw Event
      ↓
Normalize
      ↓
Attendance Transaction
      ↓
Daily Attendance
```

Example:

```text
09:02:13   EMP001   FACE   checkIn
13:01:22   EMP001   FACE   checkOut
14:02:11   EMP001   FACE   checkIn
18:04:32   EMP001   FACE   checkOut
```

The attendance engine can then calculate:

```text
First IN
Lunch OUT
Lunch IN
Last OUT
Working Hours
Late
Early Leave
Overtime
```

---

# 30. Multiple Hikvision Devices

For multiple terminals, use the same webhook:

```text
https://attendance.example.com/api/hikvision/events
```

Example:

```text
MinMoe #1
Location: Lungla
192.168.10.50
        │
        │
        ├──────────────┐
                       │
MinMoe #2              │
Location: Etah         │
192.168.20.50          │
        │              │
        ├──────────────┤
                       ▼
              /api/hikvision/events
                       │
                       ▼
                Laravel API
                       │
                       ▼
             Identify Device
                       │
                       ▼
             hikvision_devices
```

For a simple implementation, identify the device by source IP:

```php
$request->ip()
```

For a more robust multi-site deployment, also capture and use the device identifier/serial/device information contained in the actual payload where available.

---

# 31. Employee Mapping

Do not automatically assume:

```text
Hikvision employeeNoString
=
Your employee database ID
```

unless you deliberately enforce that rule.

A robust design is:

```text
Hikvision Employee No
        ↓
Employee Mapping
        ↓
Your Employee ID
        ↓
Employee Master
```

For example:

```text
EMP001 → Employee ID 125
EMP002 → Employee ID 126
EMP003 → Employee ID 127
```

This becomes especially useful when different terminals or locations are involved.

---

# 32. Security Recommendations

The webhook should be protected as much as the terminal allows.

Recommended:

```text
HTTPS
+
Firewall/WAF
+
IP allow-list where practical
+
Webhook authentication where supported
+
Raw event logging
+
Rate limiting
+
Duplicate protection
```

Ideal architecture:

```text
Hikvision
   │
   │ HTTPS
   ▼
Firewall / WAF
   │
   ▼
Apache / Nginx
   │
   ▼
Laravel API
   │
   ▼
Queue
```

Do not expose the device itself directly to the internet just to implement push attendance.

---

# 33. Final Laravel Folder Structure

Recommended:

```text
app/
│
├── Http/
│   └── Controllers/
│       └── Api/
│           └── HikvisionEventController.php
│
├── Jobs/
│   └── ProcessHikvisionEvent.php
│
├── Models/
│   ├── HikvisionDevice.php
│   ├── HikvisionEvent.php
│   └── AttendanceLog.php
│
└── Services/
    └── HikvisionEventParser.php
```

Routes:

```text
routes/
└── api.php
```

Migrations:

```text
database/
└── migrations/
    ├── create_hikvision_devices_table.php
    ├── create_hikvision_events_table.php
    └── create_attendance_logs_table.php
```

---

# 34. Complete End-to-End Data Flow

```text
                    DS-K1T320EFWX
                    MinMoe Terminal
                          │
                          │ Face Recognition
                          ▼
                    Access Event
                          │
                          │ HTTPS POST
                          ▼
       https://attendance.example.com
              /api/hikvision/events
                          │
                          ▼
             HikvisionEventController
                          │
                          ▼
                HikvisionEventParser
                          │
                          ▼
                 hikvision_events
                          │
                          │ PENDING
                          ▼
              ProcessHikvisionEvent
                          │
                          ▼
                  attendance_logs
                          │
                          ▼
                 Attendance Engine
                          │
             ┌────────────┴────────────┐
             ▼                         ▼
       HR/Attendance                Reports
```

---

# 35. Recommended Implementation Sequence

Follow this order:

### Phase 1 — Laravel API

Create:

```text
POST /api/hikvision/events
```

### Phase 2 — Database

Create:

```text
hikvision_devices
hikvision_events
attendance_logs
```

### Phase 3 — Postman

Test:

```text
XML → Laravel → MySQL
```

and:

```text
JSON → Laravel → MySQL
```

### Phase 4 — Duplicate Protection

Send the same event twice and verify only one attendance transaction is created.

### Phase 5 — Queue

Configure:

```text
Redis
+
ProcessHikvisionEvent
```

### Phase 6 — Real Terminal

Configure:

```text
DS-K1T320EFWX
       ↓
HTTPS POST
       ↓
https://attendance.example.com/api/hikvision/events
```

### Phase 7 — Capture Real Payload

Inspect the actual DS-K1T320EFWX event and adjust the parser if necessary.

### Phase 8 — Attendance Engine

Implement:

```text
IN
OUT
Late
Early Leave
Overtime
Daily Summary
```

---

# 36. Final Production URL

If your Laravel application is:

```text
https://attendance.duncanbd.net
```

the Hikvision webhook URL is:

```text
https://attendance.duncanbd.net/api/hikvision/events
```

Configuration:

```text
Method:    POST
Protocol:  HTTPS
Port:      443
URL:       /api/hikvision/events
```

The complete flow is:

```text
DS-K1T320EFWX
      │
      │ HTTPS POST
      ▼
https://attendance.duncanbd.net/api/hikvision/events
      │
      ▼
Laravel Controller
      │
      ▼
hikvision_events
      │
      ▼
Redis Queue
      │
      ▼
attendance_logs
```

---

# 37. Important Final Note

Do not finalize the production parser solely from a generic Hikvision event example.

First capture one real event from the **DS-K1T320EFWX** running the firmware you actually have. Hikvision event fields and payload structures can vary by firmware/configuration.

The safest implementation is:

```text
Postman
   ↓
Laravel webhook
   ↓
Database
   ↓
Real DS-K1T320EFWX
   ↓
Capture actual payload
   ↓
Adjust parser
   ↓
Queue processing
   ↓
Attendance
```

This gives you a reliable Push/Webhook integration while preserving the original device event for troubleshooting and future compatibility.
