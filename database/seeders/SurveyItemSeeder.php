<?php

namespace Database\Seeders;

use App\Models\SurveyItem;
use Illuminate\Database\Seeder;

class SurveyItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SurveyItem::factory(100)->create();
    }
}
