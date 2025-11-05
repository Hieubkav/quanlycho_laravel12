<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UnitSeeder::class,
            ProductSeeder::class,
            MarketSeeder::class,
            SaleSeeder::class,
            SurveySeeder::class,
            SurveyItemSeeder::class,
            ReportSeeder::class,
            ReportItemSeeder::class,
        ]);

        // Create default setting
        \App\Models\Setting::create([
            'key' => 'global',
            'brand_name' => 'Quan Ly Cho',
            'active' => true,
            'order' => 1,
        ]);

        // Create admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'phone' => '0123456789',
            'active' => true,
            'order' => 1,
        ]);
    }
}
