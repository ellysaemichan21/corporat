<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VipEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            ['name' => 'Henry Cavill', 'role' => 'driver', 'email' => 'henry@bespoke.com'],
            ['name' => 'Dilraba', 'role' => 'sorter', 'email' => 'dilraba@bespoke.com'],
            ['name' => 'Park Gyu Young', 'role' => 'washer', 'email' => 'park@bespoke.com'],
            ['name' => 'Go Younjung', 'role' => 'presser', 'email' => 'go@bespoke.com'],
            ['name' => 'Im Jinah', 'role' => 'packer', 'email' => 'im@bespoke.com'],
            ['name' => 'Oscar Isaac', 'role' => 'driver', 'email' => 'oscar@bespoke.com'],
        ];

        foreach ($employees as $employee) {
            User::updateOrCreate(
                ['email' => $employee['email']],
                [
                    'name' => $employee['name'],
                    'role' => $employee['role'],
                    'password' => Hash::make('password123'),
                ]
            );
        }
    }
}
