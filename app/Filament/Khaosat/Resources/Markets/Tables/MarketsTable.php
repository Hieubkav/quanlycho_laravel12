<?php

namespace App\Filament\Khaosat\Resources\Markets\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MarketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên chợ')
                    ->icon('heroicon-m-building-storefront')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('address')
                    ->label('Địa chỉ')
                    ->searchable()
                    ->limit(50),

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
