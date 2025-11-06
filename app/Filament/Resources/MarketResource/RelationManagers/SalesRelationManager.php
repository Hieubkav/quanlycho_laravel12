<?php

namespace App\Filament\Resources\MarketResource\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalesRelationManager extends RelationManager
{
    protected static string $relationship = 'sales';

    protected static ?string $title = 'Nhân viên bán hàng';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $name = 'market-sales';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Họ tên')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('phone')
                    ->label('Số điện thoại')
                    ->searchable(),

                TextColumn::make('address')
                    ->label('Địa chỉ')
                    ->limit(50)
                    ->toggleable(),

                IconColumn::make('active')
                    ->label('Kích hoạt')
                    ->boolean(),
            ])
            ->defaultSort('name')
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Gán nhân viên')
                    ->preloadRecordSelect()
                    ->color('primary'),
            ])
            ->recordActions([
                DetachAction::make()
                    ->label('Gỡ')
                    ->color('danger'),
            ])
            ->bulkActions([
                DetachBulkAction::make()
                    ->label('Gỡ đã chọn'),
            ]);
    }
}
