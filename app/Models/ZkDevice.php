<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(ZkAttendanceLog::class, 'device_id');
    }

    public function employeeMappings(): HasMany
    {
        return $this->hasMany(ZkEmployeeMapping::class, 'device_id');
    }
}
