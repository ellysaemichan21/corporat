<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::firstOrCreate(
            ['email' => 'laundry@gmail.com'],
            [
                'name'     => 'Laundry Admin',
                'password' => Hash::make('password'),
            ]
        );

        $this->command->info($admin->wasRecentlyCreated
            ? '  ✔ Admin account created: laundry@gmail.com'
            : '  ⟳ Admin account already exists: laundry@gmail.com');
    }
}
