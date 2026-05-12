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
            // If it's a walk-in drop-off, we skip driver assignments entirely
            if ($transaction->delivery_method !== 'dropoff') {
                $transaction->driver_id          ??= Employee::where('role', 'driver')->inRandomOrder()->first()?->id;
                $transaction->delivery_driver_id ??= Employee::where('role', 'driver')->inRandomOrder()->first()?->id;
            }
            
            $transaction->sorter_id          ??= Employee::where('role', 'sorter')->inRandomOrder()->first()?->id;
            $transaction->washer_id          ??= Employee::where('role', 'washer')->inRandomOrder()->first()?->id;
            $transaction->presser_id         ??= Employee::where('role', 'presser')->inRandomOrder()->first()?->id;
            $transaction->packer_id          ??= Employee::where('role', 'packer')->inRandomOrder()->first()?->id;
        });

        static::saving(function (Transaction $transaction) {
            $transaction->recalculatePricing();
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
    public function delivery_driver() { return $this->belongsTo(Employee::class, 'delivery_driver_id'); }
    public function review() { return $this->hasOne(Review::class); }
    public function promo() { return $this->belongsTo(Promo::class, 'promo_id'); }

    public function getTotalWeightAttribute()
    {
        return $this->details->sum('weight');
    }

    /**
     * Internal method to calculate all pricing variables.
     */
    public function recalculatePricing()
    {
        // Don't calculate if we don't have an ID yet, relation won't load properly in all cases
        if (!$this->exists) return;

        $details = $this->details()->get();
        
        $subtotal = $details->sum(function($detail) {
            return ($detail->weight ?? 0) * ($detail->unit_price ?? 0);
        });

        // Apply ASAP Surcharge
        $asapSurcharge = 0;
        if ($this->is_priority) {
            $multiplier = $this->is_corporate ? 0.25 : 0.20; // 25% for corporate, 20% for personal
            $asapSurcharge = $subtotal * $multiplier;
        }

        // Calculate Delivery Fee based on Total Weight
        $totalWeight = $this->total_weight;
        $deliveryFee = 0;
        
        // Only calculate delivery fee if delivery method is not 'dropoff' (walk-in)
        if ($this->delivery_method !== 'dropoff') {
            if ($this->is_corporate) {
                if ($totalWeight <= 50) {
                    $deliveryFee = 250000;
                } elseif ($totalWeight <= 150) {
                    $deliveryFee = 500000;
                } else {
                    $deliveryFee = 1000000;
                }
            } else {
                if ($totalWeight <= 3) {
                    $deliveryFee = 50000;
                } elseif ($totalWeight <= 7) {
                    $deliveryFee = 100000;
                } else {
                    $deliveryFee = 150000;
                }
            }
        }

        // Cap delivery fee at 50% of subtotal so it never exceeds the service cost
        $maxDelivery = $subtotal * 0.50;
        if ($deliveryFee > $maxDelivery && $subtotal > 0) {
            $deliveryFee = round($maxDelivery);
        }

        // Base Grand total before promo
        $grandTotal = $subtotal + $asapSurcharge + $deliveryFee;

        // Apply Promo Discount
        $promoDiscount = 0;
        if ($this->promo_id && $this->promo) {
            $promo = $this->promo;
            if ($promo->type === 'percent' || $promo->type === 'percentage') {
                $promoDiscount = $grandTotal * ($promo->value / 100);
            } else {
                $promoDiscount = $promo->value;
            }
        }

        $grandTotal -= $promoDiscount;
        if ($grandTotal < 0) $grandTotal = 0;

        $this->subtotal = $subtotal;
        $this->asap_surcharge = $asapSurcharge;
        $this->delivery_fee = $deliveryFee;
        $this->promo_discount = $promoDiscount;
        $this->total_price = $grandTotal;
    }

    /**
     * Force recalculate and persist the final total_price.
     */
    public function syncTotal()
    {
        $this->recalculatePricing();
        $this->saveQuietly();
        
        return $this->total_price;
    }
}