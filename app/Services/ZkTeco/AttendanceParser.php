<?php

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
