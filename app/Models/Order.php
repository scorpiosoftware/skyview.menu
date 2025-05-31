<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'table',
        'address',
        'name',
        'phone',
        'site',
        'order',
        'total',
        'session_id',
        'status',
        'note'
    ];
    protected $casts = [
        'order' => 'array',
    ];
}
