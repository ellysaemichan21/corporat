<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manifest extends Model
{
    use HasFactory;
    protected $guarded = [];

    // The driver assigned to this run
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    // The destination apartment
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    // All the laundry bags inside this specific van
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
