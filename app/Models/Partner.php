<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;
    protected $guarded = [];

    // A partner (apartment) has many residents (customers) and many deliveries (manifests)
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function manifests()
    {
        return $this->hasMany(Manifest::class);
    }
}
