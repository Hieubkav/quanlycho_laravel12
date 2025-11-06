<?php

namespace App\Filament\Resources\SurveyResource\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SurveyItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'surveyItems';

    protected static ?string $title = 'Sản phẩm khảo sát';

    protected static ?string $modelLabel = 'Sản phẩm';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('Sản phẩm')
                    ->relationship('product', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

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

                TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order', 'asc')
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
