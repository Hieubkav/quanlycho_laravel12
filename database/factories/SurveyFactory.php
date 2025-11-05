<?php

namespace Database\Factories;

use App\Models\Market;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Survey>
 */
class SurveyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'market_id' => Market::inRandomOrder()->first()?->id ?? Market::factory(),
            'sale_id' => Sale::inRandomOrder()->first()?->id ?? Sale::factory(),
            'survey_day' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'notes' => $this->faker->optional(0.7)->sentence(),
            'active' => $this->faker->boolean(95),
            'order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
