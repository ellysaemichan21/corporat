<?php

use App\Models\ServiceTier;
use App\Models\Service;

$essential = ServiceTier::firstOrCreate(['name' => 'Essential'], ['description' => 'Standard everyday laundry care']);
$signature = ServiceTier::firstOrCreate(['name' => 'Signature'], ['description' => 'Premium fabric care']);
$bespoke   = ServiceTier::firstOrCreate(['name' => 'Bespoke'],   ['description' => 'Luxury structural garment care']);

Service::firstOrCreate(['name' => 'Regular Wash',       'service_tier_id' => $essential->id], ['price' => 8000,   'unit_type' => 'kg']);
Service::firstOrCreate(['name' => 'Wash & Iron',        'service_tier_id' => $essential->id], ['price' => 12000,  'unit_type' => 'kg']);
Service::firstOrCreate(['name' => 'Express Wash',       'service_tier_id' => $essential->id], ['price' => 18000,  'unit_type' => 'kg']);

Service::firstOrCreate(['name' => 'Delicate Wash',      'service_tier_id' => $signature->id], ['price' => 25000,  'unit_type' => 'pcs']);
Service::firstOrCreate(['name' => 'Premium Dry Clean',  'service_tier_id' => $signature->id], ['price' => 35000,  'unit_type' => 'pcs']);
Service::firstOrCreate(['name' => 'Silk & Lace Care',   'service_tier_id' => $signature->id], ['price' => 45000,  'unit_type' => 'pcs']);

Service::firstOrCreate(['name' => 'Suit Deep Clean',     'service_tier_id' => $bespoke->id], ['price' => 75000,  'unit_type' => 'pcs']);
Service::firstOrCreate(['name' => 'Gown Restoration',    'service_tier_id' => $bespoke->id], ['price' => 150000, 'unit_type' => 'pcs']);
Service::firstOrCreate(['name' => 'Leather Jacket Care', 'service_tier_id' => $bespoke->id], ['price' => 120000, 'unit_type' => 'pcs']);

echo 'Seeded ' . Service::count() . ' services across ' . ServiceTier::count() . ' tiers.' . PHP_EOL;
