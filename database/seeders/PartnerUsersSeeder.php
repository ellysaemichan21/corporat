<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\Promo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PartnerUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Map partner name → login credentials
        $accounts = [
            'The Ritz-Carlton Jakarta'          => ['email' => 'ritz.jakarta@partner.com',      'contact' => 'Michael Tan'],
            'Marriott Grand Ballroom Bali'       => ['email' => 'marriott.bali@partner.com',     'contact' => 'Sarah Wijaya'],
            'Siloam Hospitals Kebon Jeruk'       => ['email' => 'siloam.kj@partner.com',         'contact' => 'Dr. Andi Pratama'],
            'RS Pondok Indah'                    => ['email' => 'rspi@partner.com',              'contact' => 'Rina Kusuma'],
            'Plataran Heritage Borobudur'        => ['email' => 'plataran.borobudur@partner.com','contact' => 'Budi Santoso'],
            'Bandar Jakarta Restaurant'          => ['email' => 'bandarjkt@partner.com',         'contact' => 'Dewi Lestari'],
            'Fitness First Pacific Place'        => ['email' => 'fitnessfirst.pp@partner.com',   'contact' => 'Kevin Halim'],
            'Celebrity Fitness Grand Indonesia'  => ['email' => 'celfit.gi@partner.com',         'contact' => 'Melissa Putri'],
            'Gojek Indonesia HQ'                 => ['email' => 'gojek.hq@partner.com',          'contact' => 'Rizky Fauzan'],
            'Tokopedia Tower'                    => ['email' => 'tokped.tower@partner.com',      'contact' => 'Hana Sari'],
        ];

        foreach ($accounts as $partnerName => $info) {
            $partner = Partner::where('name', $partnerName)->first();
            if (!$partner) {
                $this->command->warn("  ⚠ Partner not found: {$partnerName}");
                continue;
            }

            $user = User::firstOrCreate(
                ['email' => $info['email']],
                [
                    'name'       => $info['contact'],
                    'password'   => Hash::make('partner123'),
                    'partner_id' => $partner->id,
                ]
            );

            if ($user->wasRecentlyCreated) {
                $this->command->line("  ✔ Created user: {$info['email']} → {$partnerName}");
            } else {
                // Ensure partner_id is set even if user existed
                $user->update(['partner_id' => $partner->id]);
                $this->command->line("  ⟳ Updated user: {$info['email']}");
            }
        }

        // ── Create the PARTNER15 promo ───────────────────────────────────────
        $promo = Promo::firstOrCreate(
            ['code' => 'PARTNER15'],
            [
                'description' => 'Exclusive 15% B2B discount for all registered Corporate Partners. Applied automatically on login.',
                'type'        => 'percent',
                'value'       => 15,
                'min_order'   => 0,
                'expires_at'  => null, // never expires
                'is_active'   => true,
            ]
        );

        $this->command->info($promo->wasRecentlyCreated
            ? '  ✔ Promo PARTNER15 (15%) created.'
            : '  ⟳ Promo PARTNER15 already exists.');

        $this->command->info('Done! All partner user accounts are ready. Default password: partner123');
    }
}
