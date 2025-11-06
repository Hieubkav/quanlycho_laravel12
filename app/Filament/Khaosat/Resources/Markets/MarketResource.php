<?php

namespace App\Filament\Khaosat\Resources\Markets;

use App\Filament\Khaosat\Resources\Markets\Pages\ListMarkets;
use App\Filament\Khaosat\Resources\Markets\Pages\ViewMarket;
use App\Filament\Khaosat\Resources\Markets\Schemas\MarketInfolist;
use App\Filament\Khaosat\Resources\Markets\Tables\MarketsTable;
use App\Models\Market;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MarketResource extends Resource
{
    protected static ?string $model = Market::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $navigationLabel = 'Chợ';

    protected static ?string $modelLabel = 'chợ';

    protected static ?string $pluralModelLabel = 'chợ';

    protected static ?int $navigationSort = 3;

    public static function infolist(Schema $schema): Schema
    {
        return MarketInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MarketsTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $sale = auth()->guard('sale')->user();

        return parent::getEloquentQuery()
            ->whereHas('sales', function ($query) use ($sale) {
                $query->where('sales.id', $sale->id);
            })
            ->where('active', true)
            ->orderBy('order');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMarkets::route('/'),
            'view' => ViewMarket::route('/{record}'),
        ];
    }
}
