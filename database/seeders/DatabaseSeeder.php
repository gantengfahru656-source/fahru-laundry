<?php

namespace Database\Seeders;

use App\Models\LaundryService;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@laundry.com'],
            ['name' => 'Administrator', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'kasir@laundry.com'],
            ['name' => 'Kasir Laundry', 'password' => Hash::make('password'), 'role' => 'kasir']
        );

        $services = [
            ['name' => 'Cuci Kering Kiloan', 'unit_type' => 'kg', 'price_per_kg' => 7000, 'price_per_unit' => 7000, 'estimated_time' => '3-4 hari', 'description' => 'Cuci kering kiloan dengan hasil bersih dan rapi.'],
            ['name' => 'Cuci Setrika', 'unit_type' => 'kg', 'price_per_kg' => 12000, 'price_per_unit' => 12000, 'estimated_time' => '1-2 hari', 'description' => 'Cuci lengkap, kering, dan setrika.'],
            ['name' => 'Express', 'unit_type' => 'kg', 'price_per_kg' => 25000, 'price_per_unit' => 25000, 'estimated_time' => '5 jam', 'description' => 'Layanan pencucian express dalam hitungan jam.'],
            ['name' => 'Satuan / Bedcover', 'unit_type' => 'pcs', 'price_per_kg' => 35000, 'price_per_unit' => 35000, 'estimated_time' => '2-3 hari', 'description' => 'Layanan pencucian per pcs atau satuan.'],
        ];

        foreach ($services as $service) {
            LaundryService::updateOrCreate(['name' => $service['name']], $service);
        }
    }
}
