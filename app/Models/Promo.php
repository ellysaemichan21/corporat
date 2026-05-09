<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'code', 'description', 'type', 'value', 'min_order', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'is_active'  => 'boolean',
        'value'      => 'decimal:2',
        'min_order'  => 'decimal:2',
    ];
}
