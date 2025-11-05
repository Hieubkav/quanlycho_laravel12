<?php

namespace Database\Seeders;

use App\Models\ReportItem;
use Illuminate\Database\Seeder;

class ReportItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReportItem::factory(50)->create();
    }
}
