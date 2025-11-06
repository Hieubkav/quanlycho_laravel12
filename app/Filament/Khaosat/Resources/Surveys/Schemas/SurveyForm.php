<?php

namespace App\Filament\Khaosat\Resources\Surveys\Schemas;

use App\Models\Product;
use App\Models\Sale;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SurveyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('market_id')
                    ->label('Chợ')
                    ->options(function () {
                        /** @var Sale $sale */
                        $sale = auth()->guard('sale')->user();

                        return $sale->markets()
                            ->where('active', true)
                            ->orderBy('order')
                            ->pluck('name', 'id');
                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->native(false),

                DatePicker::make('survey_day')
                    ->label('Ngày khảo sát')
                    ->required()
                    ->default(now())
                    ->native(false)
                    ->displayFormat('d/m/Y'),

                Hidden::make('sale_id')
                    ->default(fn () => auth()->guard('sale')->id()),

                Textarea::make('notes')
                    ->label('Ghi chú')
                    ->rows(2)
                    ->columnSpanFull(),

                Repeater::make('surveyItems')
                    ->relationship()
                    ->label('Giá sản phẩm')
                    ->schema([
                        Select::make('product_id')
                            ->label('Sản phẩm')
                            ->options(
                                Product::where('active', true)
                                    ->where('is_default', true)
                                    ->orderBy('order')
                                    ->get()
                                    ->mapWithKeys(fn ($product) => [
                                        $product->id => $product->name.' ('.$product->unit->name.')',
                                    ])
                            )
                            ->required()
                            ->searchable()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->native(false)
                            ->columnSpan(2),

                        TextInput::make('price')
                            ->label('Giá')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->suffix('đ')
                            ->columnSpan(1),

                        Textarea::make('notes')
                            ->label('Ghi chú')
                            ->rows(1)
                            ->columnSpan(1),
                    ])
                    ->columns(4)
                    ->defaultItems(0)
                    ->addActionLabel('Thêm sản phẩm')
                    ->reorderable(false)
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => Product::find($state['product_id'])?->name ?? 'Sản phẩm mới')
                    ->deleteAction(
                        fn ($action) => $action->requiresConfirmation()
                    )
                    ->columnSpanFull(),
            ]);
    }
}
