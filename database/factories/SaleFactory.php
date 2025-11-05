<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $salesPeople = [
            ['name' => 'Nguyễn Thị Mai', 'phone' => '0901234567', 'address' => '123 Đường Nguyễn Văn Linh, Ninh Kiều, Cần Thơ', 'notes' => 'Nhân viên bán hàng kinh nghiệm với 5 năm trong ngành'],
            ['name' => 'Trần Văn Hùng', 'phone' => '0912345678', 'address' => '456 Đường 30/4, Bình Thạnh, Cần Thơ', 'notes' => 'Chuyên viên khảo sát giá cả tại các chợ'],
            ['name' => 'Lê Thị Lan', 'phone' => '0923456789', 'address' => '789 Đường Xóm Chiếu, Ninh Kiều, Cần Thơ', 'notes' => 'Thành thạo việc thu thập dữ liệu thực tế'],
            ['name' => 'Phạm Văn Đức', 'phone' => '0934567890', 'address' => '321 Đường An Cư, Ninh Kiều, Cần Thơ', 'notes' => 'Nhân viên bán hàng chuyên nghiệp'],
            ['name' => 'Hoàng Thị Linh', 'phone' => '0945678901', 'address' => '654 Đường Tây Đô, Ninh Kiều, Cần Thơ', 'notes' => 'Kinh nghiệm làm việc với các chợ địa phương'],
            ['name' => 'Đỗ Văn Minh', 'phone' => '0956789012', 'address' => '987 Đường Nguyễn An Ninh, Ninh Kiều, Cần Thơ', 'notes' => 'Chuyên viên phân tích giá cả thị trường'],
            ['name' => 'Vũ Thị Hoa', 'phone' => '0967890123', 'address' => '147 Đường Ô Môn, Ô Môn, Cần Thơ', 'notes' => 'Nhân viên khảo sát tại huyện Ô Môn'],
            ['name' => 'Bùi Văn Tùng', 'phone' => '0978901234', 'address' => '258 Đường Phong Điền, Phong Điền, Cần Thơ', 'notes' => 'Chuyên viên thu thập dữ liệu trái cây'],
        ];

        $sale = $this->faker->randomElement($salesPeople);

        return [
            'name' => $sale['name'],
            'phone' => $sale['phone'],
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password123'),
            'address' => $sale['address'],
            'notes' => $sale['notes'],
            'active' => $this->faker->boolean(95), // 95% active
            'order' => fake()->numberBetween(1, 100),
        ];
    }
}
