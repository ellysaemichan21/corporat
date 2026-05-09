<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name', 'photo', 'phone', 'role', 'join_date', 'status',
    ];

    protected $casts = [
        'join_date' => 'date',
    ];
}
