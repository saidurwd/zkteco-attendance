<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZkDeviceCommand extends Model
{
    protected $fillable = [
        'device_id',
        'command_id',
        'command',
        'status',
        'response',
        'sent_at',
        'completed_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(ZkDevice::class);
    }
}
