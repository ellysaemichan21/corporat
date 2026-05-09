<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($detail) {
            $transaction = $detail->transaction()->first();
            if ($transaction) {
                $transaction->syncTotal();
            }
        });

        static::deleted(function ($detail) {
            $transaction = $detail->transaction()->first();
            if ($transaction) {
                $transaction->syncTotal();
            }
        });
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->weight * $this->unit_price;
    }
}
