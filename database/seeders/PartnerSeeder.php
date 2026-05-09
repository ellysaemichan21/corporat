<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            // 🏨 Hotels
            [
                'name'                => 'The Ritz-Carlton Jakarta',
                'contact_person'      => 'Michael Tan',
                'address'             => 'Jl. DR. Ide Anak Agung Gde Agung No.1, Kuningan, Jakarta Selatan',
                'contract_start_date' => '2024-01-15',
            ],
            [
                'name'                => 'Marriott Grand Ballroom Bali',
                'contact_person'      => 'Sarah Wijaya',
                'address'             => 'Jl. Kartika Plaza, Kuta, Kabupaten Badung, Bali',
                'contract_start_date' => '2024-03-01',
            ],

            // 🏥 Hospitals
            [
                'name'                => 'Siloam Hospitals Kebon Jeruk',
                'contact_person'      => 'Dr. Andi Pratama',
                'address'             => 'Jl. Perjuangan No.8, Kebon Jeruk, Jakarta Barat',
                'contract_start_date' => '2024-02-10',
            ],
            [
                'name'                => 'RS Pondok Indah',
                'contact_person'      => 'Rina Kusuma',
                'address'             => 'Jl. Metro Duta Kav. UE, Pondok Indah, Jakarta Selatan',
                'contract_start_date' => '2024-04-05',
            ],

            // 🍽️ Restaurants
            [
                'name'                => 'Plataran Heritage Borobudur',
                'contact_person'      => 'Budi Santoso',
                'address'             => 'Jl. Medang No.1, Borobudur, Magelang, Jawa Tengah',
                'contract_start_date' => '2024-05-20',
            ],
            [
                'name'                => 'Bandar Jakarta Restaurant',
                'contact_person'      => 'Dewi Lestari',
                'address'             => 'Jl. Lodan Timur No.7, Ancol, Jakarta Utara',
                'contract_start_date' => '2024-06-01',
            ],

            // 💪 Gyms
            [
                'name'                => 'Fitness First Pacific Place',
                'contact_person'      => 'Kevin Halim',
                'address'             => 'Jl. Jend. Sudirman Kav. 52-53, SCBD, Jakarta Selatan',
                'contract_start_date' => '2024-03-15',
            ],
            [
                'name'                => 'Celebrity Fitness Grand Indonesia',
                'contact_person'      => 'Melissa Putri',
                'address'             => 'Jl. MH Thamrin No.1, Menteng, Jakarta Pusat',
                'contract_start_date' => '2024-07-01',
            ],

            // 🏢 Corporate Offices
            [
                'name'                => 'Gojek Indonesia HQ',
                'contact_person'      => 'Rizky Fauzan',
                'address'             => 'Jl. Kemang Selatan No.99, Kemang, Jakarta Selatan',
                'contract_start_date' => '2024-08-01',
            ],
            [
                'name'                => 'Tokopedia Tower',
                'contact_person'      => 'Hana Sari',
                'address'             => 'Jl. Prof. DR. Satrio Kav. 11, Kuningan, Jakarta Selatan',
                'contract_start_date' => '2024-09-10',
            ],
        ];

        foreach ($partners as $partner) {
            $exists = Partner::where('name', $partner['name'])->exists();
            if ($exists) {
                $this->command->line("  ⚠ Skipped (already exists): {$partner['name']}");
                continue;
            }
            Partner::create($partner);
            $this->command->line("  ✔ Added: {$partner['name']}");
        }

        $this->command->info('Done! 10 partners have been seeded.');
    }
}
