<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Market>
 */
class MarketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $usedMarkets = [];

        $markets = [
            ['name' => 'Chợ Cồn', 'address' => 'Đường Nguyễn Văn Linh, Phường Cồn, Ninh Kiều, Cần Thơ', 'notes' => 'Chợ nổi tiếng với hải sản tươi sống'],
            ['name' => 'Chợ Xóm Chiếu', 'address' => 'Đường Xóm Chiếu, Phường Xóm Chiếu, Ninh Kiều, Cần Thơ', 'notes' => 'Chợ truyền thống với nhiều loại rau củ'],
            ['name' => 'Chợ Đồn', 'address' => 'Đường Nguyễn An Ninh, Phường An Nghiệp, Ninh Kiều, Cần Thơ', 'notes' => 'Chợ bán sỉ và lẻ thực phẩm'],
            ['name' => 'Chợ An Cư', 'address' => 'Đường An Cư, Phường An Cư, Ninh Kiều, Cần Thơ', 'notes' => 'Chợ nhỏ với sản phẩm địa phương'],
            ['name' => 'Chợ Bình Thạnh', 'address' => 'Đường 30/4, Phường Bình Thạnh, Ninh Kiều, Cần Thơ', 'notes' => 'Chợ dân sinh với giá cả phải chăng'],
            ['name' => 'Chợ Tây Đô', 'address' => 'Đường Tây Đô, Phường Tây Đô, Ninh Kiều, Cần Thơ', 'notes' => 'Chợ hiện đại với nhiều tiện ích'],
            ['name' => 'Chợ Ô Môn', 'address' => 'Đường Nguyễn Trung Trực, Thị trấn Ô Môn, Ô Môn, Cần Thơ', 'notes' => 'Chợ huyện với sản vật địa phương'],
            ['name' => 'Chợ Phong Điền', 'address' => 'Đường Nguyễn Văn Linh, Thị trấn Phong Điển, Phong Điền, Cần Thơ', 'notes' => 'Chợ huyện với trái cây đặc trưng'],
        ];

        // Filter out used markets
        $availableMarkets = array_filter($markets, function ($market) use ($usedMarkets) {
            return ! in_array($market['name'], $usedMarkets);
        });

        // If all markets are used, reset the list
        if (empty($availableMarkets)) {
            $usedMarkets = [];
            $availableMarkets = $markets;
        }

        $market = $this->faker->randomElement($availableMarkets);
        $usedMarkets[] = $market['name'];

        return [
            'name' => $market['name'],
            'address' => $market['address'],
            'notes' => $market['notes'],
            'active' => $this->faker->boolean(90), // 90% active
            'order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
