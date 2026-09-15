<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function device(): BelongsTo
    {
        return $this->belongsTo(HikvisionDevice::class, 'device_id');
    }

    public function hikvisionEvent(): BelongsTo
    {
        return $this->belongsTo(HikvisionEvent::class, 'hikvision_event_id');
    }
}
