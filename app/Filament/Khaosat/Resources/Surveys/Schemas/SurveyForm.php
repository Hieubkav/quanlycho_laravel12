<?php

namespace App\Filament\Khaosat\Resources\Surveys\Schemas;

use App\Models\Product;
use App\Models\Sale;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class SurveyForm
{
    public static function configure(Schema $schema): Schema
    {
        // Build price fields array BEFORE returning schema
        $products = Product::where('active', true)
            ->where('is_default', true)
            ->with('unit')
            ->orderBy('order')
            ->get();

        // Single view for ALL products (không loop View components)
        $productsTableView = View::make('filament.forms.components.products-table')
            ->viewData([
                'products' => $products,
            ])
            ->columnSpanFull();

        // Now build the complete schema
        $schemaComponents = [
            Section::make('Thông tin khảo sát')
                ->description('Chọn chợ và ngày khảo sát')
                ->schema([
                    Select::make('market_id')
                        ->label('Chợ phân công')
                        ->helperText('Chọn chợ bạn muốn khảo sát hôm nay')
                        ->options(function () {
                            /** @var Sale $sale */
                            $sale = auth()->guard('sale')->user();

                            return $sale->markets()
                                ->where('active', true)
                                ->orderBy('order')
                                ->pluck('name', 'markets.id');
                        })
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->columnSpan(1),

                    DatePicker::make('survey_day')
                        ->label('Ngày khảo sát')
                        ->required()
                        ->default(now())
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->columnSpan(1),

                    Textarea::make('notes')
                        ->label('Ghi chú chung')
                        ->rows(2)
                        ->columnSpanFull(),

                    Hidden::make('sale_id')
                        ->default(fn () => auth()->guard('sale')->id()),
                ])
                ->columns(2)
                ->collapsible(),

            Section::make('Giá sản phẩm hôm nay')
                ->description('Nhập giá cho tất cả sản phẩm (để trống nếu không có giá) • Tổng: '.count($products).' sản phẩm')
                ->schema([
                    $productsTableView,
                ])
                ->compact(),
        ];

        return $schema
            ->columns(1)
            ->components($schemaComponents);
    }
}
