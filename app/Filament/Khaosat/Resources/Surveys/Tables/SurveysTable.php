<?php

namespace App\Filament\Khaosat\Resources\Surveys\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SurveysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('survey_day')
                    ->label('Ngày khảo sát')
                    ->date('d/m/Y')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('market.name')
                    ->label('Chợ')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('items_count')
                    ->label('Số sản phẩm')
                    ->badge()
                    ->state(fn ($record) => $record->surveyItems->count())
                    ->formatStateUsing(fn ($state) => $state.' sản phẩm')
                    ->color('success')
                    ->sortable(query: function ($query, $direction) {
                        return $query->withCount('surveyItems')->orderBy('survey_items_count', $direction);
                    }),

                TextColumn::make('total_value')
                    ->label('Tổng giá trị')
                    ->state(fn ($record) => $record->surveyItems->sum('price'))
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.').' đ')
                    ->color('info')
                    ->sortable(query: function ($query, $direction) {
                        return $query->withSum('surveyItems', 'price')->orderBy('survey_items_sum_price', $direction);
                    }),

                TextColumn::make('notes')
                    ->label('Ghi chú')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('market_id')
                    ->label('Chợ')
                    ->relationship('market', 'name')
                    ->preload()
                    ->multiple(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Xem'),
                EditAction::make()
                    ->label('Sửa'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Xóa đã chọn'),
                ]),
            ])
            ->defaultSort('survey_day', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
