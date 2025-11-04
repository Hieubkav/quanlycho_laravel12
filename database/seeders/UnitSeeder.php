<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'kg', 'active' => true, 'order' => 1],
            ['name' => 'con', 'active' => true, 'order' => 2],
            ['name' => 'quả', 'active' => true, 'order' => 3],
            ['name' => 'bó', 'active' => true, 'order' => 4],
            ['name' => 'lít', 'active' => true, 'order' => 5],
        ];

        foreach ($units as $unit) {
            \App\Models\Unit::create($unit);
        }
    }
}
