<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZkRawRequest extends Model
{
    protected $fillable = [
        'serial_number',
        'method',
        'uri',
        'query_params',
        'headers',
        'body',
        'remote_ip',
        'received_at',
    ];

    protected $casts = [
        'query_params' => 'array',
        'received_at' => 'datetime',
    ];
}
