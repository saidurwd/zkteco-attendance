<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZkEmployeeMapping extends Model
{
    protected $fillable = [
        'device_id',
        'device_pin',
        'employee_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(ZkDevice::class);
    }
}
