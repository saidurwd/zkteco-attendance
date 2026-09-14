<?php

namespace App\Jobs\ZkTeco;

use App\Models\ZkAttendanceLog;
use App\Models\ZkEmployeeMapping;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessAttendance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        public int $attendanceId
    ) {
    }

    public function handle(): void
    {
        $record = ZkAttendanceLog::find($this->attendanceId);

        if (!$record) {
            Log::warning('ProcessAttendance: record not found', [
                'attendance_id' => $this->attendanceId,
            ]);

            return;
        }

        $mapping = ZkEmployeeMapping::where('device_id', $record->device_id)
            ->where('device_pin', $record->employee_pin)
            ->where('is_active', true)
            ->first();

        if (!$mapping) {
            Log::info('ProcessAttendance: no employee mapping found', [
                'attendance_id' => $this->attendanceId,
                'serial' => $record->serial_number,
                'pin' => $record->employee_pin,
            ]);

            return;
        }

        Log::info('ProcessAttendance: processed', [
            'attendance_id' => $this->attendanceId,
            'employee_code' => $mapping->employee_code,
            'attendance_time' => $record->attendance_time,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessAttendance: job failed', [
            'attendance_id' => $this->attendanceId,
            'error' => $exception->getMessage(),
        ]);
    }
}
