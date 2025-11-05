<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitResource\Pages;
use App\Models\Unit;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use BackedEnum;
use Filament\Tables;
use Filament\Tables\Table;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-scale';

    protected static ?string $navigationLabel = 'Đơn vị';

    protected static ?string $modelLabel = 'Đơn vị';

    protected static ?string $pluralModelLabel = 'Đơn vị';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('Tên đơn vị')
                    ->required()
                    ->maxLength(255),

                Toggle::make('active')
                    ->label('Kích hoạt')
                    ->default(true),

                TextInput::make('order')
                    ->label('Thứ tự')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
    return $table
    ->columns([
    Tables\Columns\TextColumn::make('name')
    ->label('Tên đơn vị')
                    ->searchable(),

    Tables\Columns\IconColumn::make('active')
                    ->label('Kích hoạt')
        ->boolean(),

    Tables\Columns\TextColumn::make('created_at')
    ->label('Ngày tạo')
                    ->dateTime()
        ->sortable()
    ->toggleable(isToggledHiddenByDefault: true),

    Tables\Columns\TextColumn::make('updated_at')
            ->label('Ngày cập nhật')
            ->dateTime()
        ->sortable()
    ->toggleable(isToggledHiddenByDefault: true),
    ])
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Trạng thái kích hoạt'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnit::route('/create'),
            'view' => Pages\ViewUnit::route('/{record}'),
            'edit' => Pages\EditUnit::route('/{record}/edit'),
        ];
    }
}
