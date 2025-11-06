<?php

namespace App\Filament\Khaosat\Resources\Surveys\Schemas;

use App\Models\Product;
use App\Models\Sale;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section as SchemaSection;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class SurveyForm
{
    public static function configure(Schema $schema): Schema
    {
        // Build products data
        $products = Product::where('active', true)
            ->where('is_default', true)
            ->with('unit')
            ->orderBy('order')
            ->get();

        // Create dynamic price fields for each product
        $priceFields = [];
        foreach ($products as $index => $product) {
            $unitName = $product->unit?->name ?? 'N/A';
            $stt = $index + 1;

            $priceFields[] = TextInput::make('prices.'.$product->id.'.price')
                ->label($stt.'. '.$product->name.' ('.$unitName.')')
                ->numeric()
                ->minValue(0)
                ->placeholder('0')
                ->suffix('đ')
                ->mask(RawJs::make(<<<'JS'
                    $money($input, ',', '.', 0)
                JS))
                ->stripCharacters([',', '.'])
                ->inlineLabel()
                ->columnSpan(1);

            $priceFields[] = Textarea::make('prices.'.$product->id.'.notes')
                ->label('Ghi chú')
                ->rows(2)
                ->placeholder('Ghi chú (tùy chọn)')
                ->inlineLabel()
                ->columnSpan(1);

            $priceFields[] = Hidden::make('prices.'.$product->id.'.product_id')
                ->default($product->id);

            $priceFields[] = Hidden::make('prices.'.$product->id.'.product_name')
                ->default($product->name);

            $priceFields[] = Hidden::make('prices.'.$product->id.'.unit_name')
                ->default($unitName);
        }

        // Now build the complete schema
        $schemaComponents = [
            SchemaSection::make('Thông tin khảo sát')
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

            SchemaSection::make('Giá sản phẩm hôm nay')
                ->description('Nhập giá cho tất cả sản phẩm (để trống nếu không có giá)')
                ->schema($priceFields)
                ->columns([
                    'default' => 1,
                    'md' => 2,
                    'xl' => 4,
                ])
                ->columnSpanFull(),
        ];

        return $schema
            ->columns(1)
            ->components($schemaComponents);
    }
}
