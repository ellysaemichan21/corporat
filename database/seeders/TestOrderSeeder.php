<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Service;
use App\Models\Promo;
use Illuminate\Support\Str;

class TestOrderSeeder extends Seeder
{
    public function run()
    {
        $service = Service::first();

        $promoInsta = Promo::where('code', 'INSTAGRAM10')->first();
        $promoTikTok = Promo::where('code', 'TIKTOK10')->first();
        $promoPartner = Promo::where('code', 'PARTNER15')->first();

        $customer1 = Customer::create(['name' => 'Jane Smith (Personal)', 'phone' => '08123456789']);
        $customer2 = Customer::create(['name' => 'MegaCorp (Corporate)', 'phone' => '08987654321']);

        $ordersData = [
            // Personal 1: 2kg (Tier 1: <= 3kg)
            [
                'customer' => $customer1, 'is_corporate' => false, 'weight' => 2, 'promo' => $promoInsta?->id, 'is_priority' => false, 'tier' => 'Essential'
            ],
            // Personal 2: 6kg (Tier 2: <= 7kg)
            [
                'customer' => $customer1, 'is_corporate' => false, 'weight' => 6, 'promo' => $promoTikTok?->id, 'is_priority' => true, 'tier' => 'Signature'
            ],
            // Personal 3: 12kg (Tier 3: > 7kg)
            [
                'customer' => $customer1, 'is_corporate' => false, 'weight' => 12, 'promo' => null, 'is_priority' => false, 'tier' => 'Bespoke'
            ],
            // Corporate 1: 40kg (Tier 1: <= 50kg)
            [
                'customer' => $customer2, 'is_corporate' => true, 'weight' => 40, 'promo' => $promoPartner?->id, 'is_priority' => false, 'tier' => 'Essential'
            ],
            // Corporate 2: 120kg (Tier 2: <= 150kg)
            [
                'customer' => $customer2, 'is_corporate' => true, 'weight' => 120, 'promo' => null, 'is_priority' => true, 'tier' => 'Signature'
            ],
            // Corporate 3: 250kg (Tier 3: > 150kg)
            [
                'customer' => $customer2, 'is_corporate' => true, 'weight' => 250, 'promo' => null, 'is_priority' => false, 'tier' => 'Bespoke'
            ],
        ];

        foreach ($ordersData as $data) {
            $t = Transaction::create([
                'invoice_code' => 'INV-' . strtoupper(Str::random(6)),
                'customer_id' => $data['customer']->id,
                'is_corporate' => $data['is_corporate'],
                'tier_level' => $data['tier'],
                'laundry_status' => 'Pending',
                'payment_status' => 'Unpaid',
                'is_priority' => $data['is_priority'],
                'promo_id' => $data['promo']
            ]);
            
            TransactionDetail::create([
                'transaction_id' => $t->id,
                'service_id' => $service->id,
                'weight' => $data['weight'],
                'unit_price' => $service->price ?? 15000
            ]);
            
            // Re-sync after adding details
            $t->syncTotal();
        }
    }
}
