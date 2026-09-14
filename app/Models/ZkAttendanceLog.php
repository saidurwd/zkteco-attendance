<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function device(): BelongsTo
    {
        return $this->belongsTo(ZkDevice::class);
    }
}
