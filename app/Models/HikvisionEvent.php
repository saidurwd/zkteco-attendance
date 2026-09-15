<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function device(): BelongsTo
    {
        return $this->belongsTo(HikvisionDevice::class, 'device_id');
    }

    public function attendance(): HasOne
    {
        return $this->hasOne(AttendanceLog::class, 'hikvision_event_id');
    }
}
