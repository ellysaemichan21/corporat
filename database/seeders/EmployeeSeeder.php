<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // Empty all employees data
        DB::table('employees')->truncate();

        $karyawans = [
            // --- SHIFT 1 ---
            ['name' => 'Michelle Yeoh', 'role' => 'manager', 'photo' => 'employees/micheleyeoh.jpeg'],
            ['name' => 'Henry Cavill', 'role' => 'driver', 'photo' => 'employees/henrycavill.jpeg'],
            ['name' => 'Mads Mikkelsen', 'role' => 'driver', 'photo' => 'employees/madmikkelsen.jpeg'],
            ['name' => 'Song Hye-kyo', 'role' => 'sorter', 'photo' => 'employees/songhyekyo.jpeg'],
            ['name' => 'Kim Ji-won', 'role' => 'washer', 'photo' => 'employees/kimjiwon.jpeg'],
            ['name' => 'Park Ji-hyo', 'role' => 'presser', 'photo' => 'employees/jihyo.jpeg'],
            ['name' => 'Han So-hee', 'role' => 'packer', 'photo' => 'employees/hansohee.jpeg'],

            // --- SHIFT 2 ---
            ['name' => 'Kim Hye-soo', 'role' => 'manager', 'photo' => 'employees/kimhyeso.jpeg'],
            ['name' => 'Oscar Isaac', 'role' => 'driver', 'photo' => 'employees/oscarisaac.jpeg'],
            ['name' => 'Don Lee', 'role' => 'driver', 'photo' => 'employees/donlee.jpeg'],
            ['name' => 'Mai Davika', 'role' => 'sorter', 'photo' => 'employees/davika.jpeg'],
            ['name' => 'Go Youn-jung', 'role' => 'washer', 'photo' => 'employees/goyounjung.jpeg'],
            ['name' => 'Im Jin-ah', 'role' => 'presser', 'photo' => 'employees/imjinah.jpeg'],
            ['name' => 'Park Gyu-young', 'role' => 'packer', 'photo' => 'employees/parkgyuyoung.jpeg'],

            // --- SHIFT 3 ---
            ['name' => 'Gemma Chan', 'role' => 'manager', 'photo' => 'employees/gemmachan.jpeg'],
            ['name' => 'Lee Byung-hun', 'role' => 'driver', 'photo' => 'employees/leebyunhun.jpeg'],
            ['name' => 'Alan Ritchson', 'role' => 'driver', 'photo' => 'employees/alanritschon.jpeg'],
            ['name' => 'Han Hyo-joo', 'role' => 'sorter', 'photo' => 'employees/hanhyojoo.jpeg'],
            ['name' => 'Seo Yea-ji', 'role' => 'washer', 'photo' => 'employees/seoyeaji.jpeg'],
            ['name' => 'Seol In-ah', 'role' => 'presser', 'photo' => 'employees/seolinah.jpeg'],
            ['name' => 'Dilraba Dilmurat', 'role' => 'packer', 'photo' => 'employees/dilraba.png'],

            // --- SHIFT 4 ---
            ['name' => 'Li Bingbing', 'role' => 'manager', 'photo' => 'employees/libingbing.jpeg'],
            ['name' => 'Jason Statham', 'role' => 'driver', 'photo' => 'employees/jasonstatham.jpeg'],
            ['name' => 'Tom Hardy', 'role' => 'driver', 'photo' => 'employees/tomhardy.jpeg'],
            ['name' => 'Engfa Waraha', 'role' => 'sorter', 'photo' => 'employees/engfa.jpeg'],
            ['name' => 'Shin Ryujin', 'role' => 'washer', 'photo' => 'employees/ryujin.jpeg'],
            ['name' => 'Bae Suzy', 'role' => 'presser', 'photo' => 'employees/baesuzy.png'],
            ['name' => 'Karina', 'role' => 'packer', 'photo' => 'employees/karina.jpeg'],
        ];

        foreach ($karyawans as $index => $data) {
            // Generate a fake phone number
            $phone = '0812-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT) . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);

            Employee::create([
                'name'      => $data['name'],
                'role'      => $data['role'],
                'photo'     => $data['photo'],
                'phone'     => $phone,
                'join_date' => now()->subMonths(rand(1, 24)),
                'status'    => 'active',
            ]);
        }
    }
}
