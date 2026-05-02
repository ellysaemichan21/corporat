<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function tier()
    {
        return $this->belongsTo(ServiceTier::class, 'service_tier_id');
    }
}