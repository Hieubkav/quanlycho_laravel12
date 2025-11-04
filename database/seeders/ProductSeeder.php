<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kg = \App\Models\Unit::where('name', 'kg')->first()->id;
        $con = \App\Models\Unit::where('name', 'con')->first()->id;
        $qua = \App\Models\Unit::where('name', 'quả')->first()->id;
        $bo = \App\Models\Unit::where('name', 'bó')->first()->id;
        $lit = \App\Models\Unit::where('name', 'lít')->first()->id;

        $products = [
            ['name' => 'Cà rốt', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 1],
            ['name' => 'Củ cải', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 2],
            ['name' => 'Khoai tây', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 3],
            ['name' => 'Ớt', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 4],
            ['name' => 'Tỏi', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 5],
            ['name' => 'Hành khô', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 6],
            ['name' => 'Gừng', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 7],
            ['name' => 'Rau muống', 'unit_id' => $bo, 'is_default' => true, 'active' => true, 'order' => 8],
            ['name' => 'Rau dền', 'unit_id' => $bo, 'is_default' => true, 'active' => true, 'order' => 9],
            ['name' => 'Rau ngót', 'unit_id' => $bo, 'is_default' => true, 'active' => true, 'order' => 10],
            ['name' => 'Cải xanh', 'unit_id' => $bo, 'is_default' => true, 'active' => true, 'order' => 11],
            ['name' => 'Cải ngọt', 'unit_id' => $bo, 'is_default' => true, 'active' => true, 'order' => 12],
            ['name' => 'Bông cải', 'unit_id' => $qua, 'is_default' => true, 'active' => true, 'order' => 13],
            ['name' => 'Cà chua', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 14],
            ['name' => 'Dưa leo', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 15],
            ['name' => 'Khế', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 16],
            ['name' => 'Ổi', 'unit_id' => $qua, 'is_default' => true, 'active' => true, 'order' => 17],
            ['name' => 'Táo', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 18],
            ['name' => 'Cam', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 19],
            ['name' => 'Chanh', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 20],
            ['name' => 'Bưởi', 'unit_id' => $qua, 'is_default' => true, 'active' => true, 'order' => 21],
            ['name' => 'Nho', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 22],
            ['name' => 'Dừa', 'unit_id' => $qua, 'is_default' => true, 'active' => true, 'order' => 23],
            ['name' => 'Thịt gà', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 24],
            ['name' => 'Thịt heo', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 25],
            ['name' => 'Thịt bò', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 26],
            ['name' => 'Cá basa', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 27],
            ['name' => 'Cá hồi', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 28],
            ['name' => 'Tôm', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 29],
            ['name' => 'Cua', 'unit_id' => $con, 'is_default' => true, 'active' => true, 'order' => 30],
            ['name' => 'Trứng gà', 'unit_id' => $qua, 'is_default' => true, 'active' => true, 'order' => 31],
            ['name' => 'Trứng vịt', 'unit_id' => $qua, 'is_default' => true, 'active' => true, 'order' => 32],
            ['name' => 'Sữa tươi', 'unit_id' => $lit, 'is_default' => true, 'active' => true, 'order' => 33],
            ['name' => 'Sữa chua', 'unit_id' => $qua, 'is_default' => true, 'active' => true, 'order' => 34],
            ['name' => 'Phô mai', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 35],
            ['name' => 'Bánh mì', 'unit_id' => $qua, 'is_default' => true, 'active' => true, 'order' => 36],
            ['name' => 'Mì gói', 'unit_id' => $qua, 'is_default' => true, 'active' => true, 'order' => 37],
            ['name' => 'Gạo', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 38],
            ['name' => 'Đường', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 39],
            ['name' => 'Muối', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 40],
            ['name' => 'Dầu ăn', 'unit_id' => $lit, 'is_default' => true, 'active' => true, 'order' => 41],
            ['name' => 'Bơ', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 42],
            ['name' => 'Cà phê', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 43],
            ['name' => 'Trà', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 44],
            ['name' => 'Sữa bột', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 45],
            ['name' => 'Đậu xanh', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 46],
            ['name' => 'Đậu đen', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 47],
            ['name' => 'Hạt điều', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 48],
            ['name' => 'Hạt dẻ', 'unit_id' => $kg, 'is_default' => true, 'active' => true, 'order' => 49],
            ['name' => 'Mít', 'unit_id' => $qua, 'is_default' => true, 'active' => true, 'order' => 50],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
