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

    public function __construct(public int $eventId) {}

    public function handle(): void
    {
        $event = HikvisionEvent::find($this->eventId);

        if (!$event) {
            return;
        }

        if ($event->processing_status === 'PROCESSED') {
            return;
        }

        if (!$event->employee_no) {
            $event->update([
                'processing_status' => 'IGNORED',
                'processing_message' => 'Employee number missing',
                'processed_at' => now(),
            ]);

            return;
        }

        try {
            DB::transaction(function () use ($event) {
                AttendanceLog::create([
                    'employee_no' => $event->employee_no,
                    'device_id' => $event->device_id,
                    'attendance_time' => $event->event_time,
                    'attendance_type' => $event->attendance_status,
                    'verify_mode' => $event->verify_mode,
                    'source' => 'HIKVISION',
                    'hikvision_event_id' => $event->id,
                ]);

                $event->update([
                    'processing_status' => 'PROCESSED',
                    'processed_at' => now(),
                    'processing_message' => null,
                ]);
            });
        } catch (\Throwable $e) {
            $event->update([
                'processing_status' => 'FAILED',
                'processing_message' => $e->getMessage(),
                'processed_at' => now(),
            ]);

            throw $e;
        }
    }
}
