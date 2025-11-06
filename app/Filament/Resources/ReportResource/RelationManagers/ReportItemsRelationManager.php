<?php

namespace App\Filament\Resources\ReportResource\RelationManagers;

use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReportItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'reportItems';

    protected static ?string $title = 'Chi tiết báo cáo';

    protected static ?string $modelLabel = 'Mục báo cáo';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('survey_id')
                    ->label('Khảo sát')
                    ->relationship('survey', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->market->name} - {$record->sale->name} ({$record->survey_day->format('d/m/Y')})"
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('product_id')
                    ->label('Sản phẩm')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('price')
                    ->label('Giá')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->step(0.01)
                    ->suffix('VNĐ'),

                Textarea::make('notes')
                    ->label('Ghi chú')
                    ->columnSpanFull(),

                Toggle::make('active')
                    ->label('Kích hoạt')
                    ->default(true),

                TextInput::make('order')
                    ->label('Thứ tự')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('survey.survey_day')
                    ->label('Ngày khảo sát')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('survey.market.name')
                    ->label('Chợ')
                    ->searchable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('survey.sale.name')
                    ->label('Nhân viên')
                    ->searchable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('product.name')
                    ->label('Sản phẩm')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('product.unit.name')
                    ->label('Đơn vị')
                    ->badge(),

                TextColumn::make('price')
                    ->label('Giá')
                    ->money('VND')
                    ->sortable(),

                TextColumn::make('notes')
                    ->label('Ghi chú')
                    ->limit(50)
                    ->toggleable(),

                IconColumn::make('active')
                    ->label('Kích hoạt')
                    ->boolean(),
            ])
            ->defaultSort('survey.survey_day', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                // Report items usually auto-generated, no create action
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
