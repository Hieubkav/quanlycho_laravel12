<?php

namespace App\Filament\Resources\SurveyResource\Schemas;

use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class SurveyFormBuilder
{
    public static function make(Schema $schema): Schema
    {
        // Create dynamic price fields for each product
        $priceFields = [];

        // Build products data for create/edit forms
        $products = Product::query()
            ->where('active', true)
            ->where('is_default', true)
            ->with('unit')
            ->orderBy('order')
            ->get();

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
        }

        return $schema
            ->schema([
                Section::make('Thông tin khảo sát')
                    ->schema([
                        Select::make('market_id')
                            ->label('Chợ')
                            ->relationship('market', 'name')
                            ->required()
                            ->live()
                            ->searchable()
                            ->preload(),

                        Select::make('sale_id')
                            ->label('Người khảo sát')
                            ->relationship(
                                name: 'sale',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query, Get $get) => $query->when(
                                    $get('market_id'),
                                    fn ($q, $marketId) => $q->whereHas('markets', fn ($mq) => $mq->where('markets.id', $marketId))
                                )
                            )
                            ->required()
                            ->searchable()
                            ->preload()
                            ->disabled(fn (Get $get) => ! $get('market_id'))
                            ->helperText('Chọn chợ trước để hiển thị danh sách người khảo sát'),

                        DatePicker::make('survey_day')
                            ->label('Ngày khảo sát')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->default(now()),

                        Toggle::make('active')
                            ->label('Kích hoạt')
                            ->default(true)
                            ->hiddenOn('create'),

                        TextInput::make('order')
                            ->label('Thứ tự')
                            ->numeric()
                            ->default(0)
                            ->hiddenOn('create'),

                        Textarea::make('notes')
                            ->label('Ghi chú chung')
                            ->rows(3)
                            ->columnSpanFull(),

                        Placeholder::make('created_at')
                            ->label('Ngày tạo')
                            ->content(fn ($record) => $record?->created_at?->format('d/m/Y H:i') ?? '-')
                            ->visibleOn('view'),

                        Placeholder::make('updated_at')
                            ->label('Cập nhật lần cuối')
                            ->content(fn ($record) => $record?->updated_at?->format('d/m/Y H:i') ?? '-')
                            ->visibleOn('view'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Giá sản phẩm')
                    ->description('Nhập giá cho các sản phẩm (để trống nếu không có giá)')
                    ->schema($priceFields)
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                        'xl' => 4,
                    ])
                    ->columnSpanFull()
                    ->hiddenOn('view'),
            ]);
    }
}
