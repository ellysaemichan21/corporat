<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function manifest() { return $this->belongsTo(Manifest::class); }
    public function logs() { return $this->hasMany(TransactionLog::class); }
    
    // NEW: Links the main ticket to the specific items inside the bag
    public function details() { return $this->hasMany(TransactionDetail::class); }
    
    // NEW: Links the ticket to the "No Lying" QC photos
    public function images() { return $this->hasMany(TransactionImage::class); }
}