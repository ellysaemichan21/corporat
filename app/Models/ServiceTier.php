<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTier extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Each tier has many specific items/prices inside it
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
