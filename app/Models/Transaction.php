<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Employee;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function (Transaction $transaction) {
            $transaction->driver_id  ??= Employee::where('role', 'driver')->inRandomOrder()->first()?->id;
            $transaction->sorter_id  ??= Employee::where('role', 'sorter')->inRandomOrder()->first()?->id;
            $transaction->washer_id  ??= Employee::where('role', 'washer')->inRandomOrder()->first()?->id;
            $transaction->presser_id ??= Employee::where('role', 'presser')->inRandomOrder()->first()?->id;
            $transaction->packer_id  ??= Employee::where('role', 'packer')->inRandomOrder()->first()?->id;
        });
    }

    public function customer() { return $this->belongsTo(Customer::class); }
    public function partner()  { return $this->belongsTo(Partner::class); }
    public function logs()     { return $this->hasMany(TransactionLog::class); }
    
    // NEW: Links the main ticket to the specific items inside the bag
    public function details() { return $this->hasMany(TransactionDetail::class); }
    
    // NEW: Links the ticket to the "No Lying" QC photos
    public function images() { return $this->hasMany(TransactionImage::class); }

    // Chain of Custody Relationships (Point to Employee model)
    public function sorter() { return $this->belongsTo(Employee::class, 'sorter_id'); }
    public function washer() { return $this->belongsTo(Employee::class, 'washer_id'); }
    public function presser() { return $this->belongsTo(Employee::class, 'presser_id'); }
    public function packer() { return $this->belongsTo(Employee::class, 'packer_id'); }
    public function driver() { return $this->belongsTo(Employee::class, 'driver_id'); }

    public function getTotalWeightAttribute()
    {
        return $this->details->sum('weight');
    }

    /**
     * Recalculate and persist the final total_price.
     */
    public function syncTotal()
    {
        // Force refresh the details to get the absolute latest from the database
        $details = $this->details()->get();
        
        $sum = $details->sum(function($detail) {
            return ($detail->weight ?? 0) * ($detail->unit_price ?? 0);
        });

        // Apply Surcharges
        $surchargeMultiplier = 1.0;
        
        if ($this->is_fast_track) {
            $surchargeMultiplier += 0.30; // +30% for Fast Track
        }
        
        if ($this->is_priority) {
            $surchargeMultiplier += 0.50; // +50% for ASAP Priority
        }

        $finalTotal = $sum * $surchargeMultiplier;

        // Use updateQuietly or manual save to avoid any infinite loops if there were observers on Transaction
        $this->total_price = $finalTotal;
        $this->save();
        
        return $finalTotal;
    }
}