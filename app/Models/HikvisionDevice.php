<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function events(): HasMany
    {
        return $this->hasMany(HikvisionEvent::class, 'device_id');
    }
}
