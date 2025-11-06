<?php

namespace App\Filament\Khaosat\Resources\Products;

use App\Filament\Khaosat\Resources\Products\Pages\ListProducts;
use App\Filament\Khaosat\Resources\Products\Pages\ViewProduct;
use App\Filament\Khaosat\Resources\Products\Schemas\ProductInfolist;
use App\Filament\Khaosat\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static ?string $navigationLabel = 'Sản Phẩm';

    protected static ?string $modelLabel = 'sản phẩm';

    protected static ?string $pluralModelLabel = 'sản phẩm';

    protected static ?int $navigationSort = 2;

    public static function infolist(Schema $schema): Schema
    {
        return ProductInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('active', true)
            ->where('is_default', true)
            ->with('unit')
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
            'index' => ListProducts::route('/'),
            'view' => ViewProduct::route('/{record}'),
        ];
    }
}
