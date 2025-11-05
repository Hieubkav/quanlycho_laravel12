<?php

namespace Database\Factories;

use App\Models\Survey;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fromDay = $this->faker->dateTimeBetween('-60 days', '-7 days')->format('Y-m-d');
        $toDay = $this->faker->dateTimeBetween($fromDay, 'now')->format('Y-m-d');

        // Lấy một số survey trong khoảng thời gian
        $surveys = Survey::whereBetween('survey_day', [$fromDay, $toDay])
            ->inRandomOrder()
            ->limit($this->faker->numberBetween(3, 10))
            ->get();

        $includedSurveyIds = $surveys->pluck('id')->toArray();

        // Tạo summary giả
        $summaryRows = [];
        if ($surveys->isNotEmpty()) {
            foreach ($surveys->take(5) as $survey) {
                $summaryRows[] = [
                    'market' => $survey->market->name ?? 'Unknown',
                    'survey_day' => $survey->survey_day,
                    'total_products' => $survey->surveyItems->count(),
                    'avg_price_change' => $this->faker->randomFloat(2, -10, 10),
                ];
            }
        }

        return [
            'from_day' => $fromDay,
            'to_day' => $toDay,
            'generated_at' => $this->faker->dateTimeBetween($toDay, 'now'),
            'created_by_admin_id' => User::where('role', 'admin')->inRandomOrder()->first()?->id ?? User::factory(['role' => 'admin']),
            'summary_rows' => $summaryRows,
            'included_survey_ids' => $includedSurveyIds,
            'order' => $this->faker->numberBetween(1, 100),
            'active' => $this->faker->boolean(90),
        ];
    }
}
