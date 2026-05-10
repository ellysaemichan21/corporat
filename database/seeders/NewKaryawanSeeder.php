<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class NewKaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            ['name' => 'Norman Reedus', 'role' => 'driver'],
            ['name' => 'Choi Min-sik', 'role' => 'driver'],
            ['name' => 'Bae Suzy', 'role' => 'sorter'],
            ['name' => 'Han So-hee', 'role' => 'washer'],
            ['name' => 'Kim Ji-won', 'role' => 'presser'],
            ['name' => 'Song Hye-kyo', 'role' => 'packer'],

            // Shift 3
            ['name' => 'Tom Hardy', 'role' => 'driver'],
            ['name' => 'Jason Statham', 'role' => 'driver'],
            ['name' => 'Zendaya', 'role' => 'sorter'],
            ['name' => 'Florence Pugh', 'role' => 'washer'],
            ['name' => 'Anya Taylor-Joy', 'role' => 'presser'],
            ['name' => 'Charlize Theron', 'role' => 'packer'],

            // Shift 4
            ['name' => 'Keanu Reeves', 'role' => 'driver'],
            ['name' => 'Ryan Gosling', 'role' => 'driver'],
            ['name' => 'Scarlett Johansson', 'role' => 'sorter'],
            ['name' => 'Jun Ji-hyun', 'role' => 'washer'],
            ['name' => 'Ana de Armas', 'role' => 'presser'],
            ['name' => 'Margot Robbie', 'role' => 'packer'],

            // Shift 5
            ['name' => 'Christian Bale', 'role' => 'driver'],
            ['name' => 'Brad Pitt', 'role' => 'driver'],
            ['name' => 'Léa Seydoux', 'role' => 'sorter'],
            ['name' => 'Kim Go-eun', 'role' => 'washer'],
            ['name' => 'Shin Hye-sun', 'role' => 'presser'],
            ['name' => 'Jeon Yeo-been', 'role' => 'packer'],
        ];

        foreach ($employees as $employee) {
            Employee::updateOrCreate(
                ['name' => $employee['name'], 'role' => $employee['role']],
                [
                    'status' => 'active',
                    'join_date' => now(),
                    'phone' => '+62 8' . rand(11111111, 99999999),
                ]
            );
        }
    }
}
