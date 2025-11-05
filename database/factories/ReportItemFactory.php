<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Report;
use App\Models\Survey;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportItem>
 */
class ReportItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $report = Report::inRandomOrder()->first();
        if (! $report) {
            $report = Report::factory()->create();
        }

        // Lấy survey từ included_survey_ids của report
        $surveyIds = $report->included_survey_ids ?? [];
        $survey = null;
        if (! empty($surveyIds)) {
            $survey = Survey::whereIn('id', $surveyIds)->inRandomOrder()->first();
        }
        if (! $survey) {
            $survey = Survey::inRandomOrder()->first() ?? Survey::factory()->create();
        }

        // Lấy product từ survey items
        $productIds = $survey->surveyItems->pluck('product_id')->toArray();
        $productId = ! empty($productIds) ? $this->faker->randomElement($productIds) : (Product::inRandomOrder()->first()?->id ?? Product::factory()->create()->id);

        // Lấy giá từ survey item hoặc tạo giá mới
        $surveyItem = $survey->surveyItems->where('product_id', $productId)->first();
        $price = $surveyItem ? $surveyItem->price : $this->faker->numberBetween(20000, 150000);

        return [
            'report_id' => $report->id,
            'survey_id' => $survey->id,
            'product_id' => $productId,
            'price' => $price,
            'notes' => $this->faker->optional(0.6)->sentence(),
            'active' => $this->faker->boolean(95),
            'order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
