<?php

namespace App\Filament\Khaosat\Resources\Products\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên sản phẩm')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('unit.name')
                    ->label('Đơn vị')
                    ->badge()
                    ->color('success'),

                TextColumn::make('notes')
                    ->label('Ghi chú')
                    ->limit(50)
                    ->toggleable(),

                TextColumn::make('order')
                    ->label('Thứ tự')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Xem'),
            ])
            ->defaultSort('order', 'asc')
            ->striped()
            ->paginated([10, 25, 50]);
    }
}
