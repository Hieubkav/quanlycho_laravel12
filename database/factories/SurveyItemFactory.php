<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Survey;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SurveyItem>
 */
class SurveyItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::inRandomOrder()->first();
        if (! $product) {
            $product = Product::factory()->create();
        }

        // Giá thực tế dựa trên loại sản phẩm (VNĐ/kg hoặc đơn vị)
        $basePrices = [
            'kg' => $this->faker->numberBetween(20000, 150000), // Rau củ
            'con' => $this->faker->numberBetween(50000, 300000), // Hải sản, gia cầm
            'quả' => $this->faker->numberBetween(10000, 80000),  // Trái cây, trứng
            'bó' => $this->faker->numberBetween(15000, 50000),   // Rau
            'lít' => $this->faker->numberBetween(25000, 80000),  // Sữa, dầu ăn
        ];

        $unit = $product->unit->name ?? 'kg';
        $price = $basePrices[$unit] ?? $this->faker->numberBetween(20000, 100000);

        return [
            'survey_id' => Survey::inRandomOrder()->first()?->id ?? Survey::factory(),
            'product_id' => $product->id,
            'price' => $price,
            'notes' => $this->faker->optional(0.5)->sentence(),
            'active' => $this->faker->boolean(98),
            'order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
