<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarketResource\Pages;
use App\Models\Market;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class MarketResource extends Resource
{
    protected static ?string $model = Market::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationLabel = 'Thị trường';

    protected static ?string $modelLabel = 'Thị trường';

    protected static ?string $pluralModelLabel = 'Thị trường';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('Tên thị trường')
                    ->required()
                    ->maxLength(255),

                Textarea::make('address')
                    ->label('Địa chỉ')
                    ->required()
                    ->columnSpanFull(),

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

    public static function table(Table $table): Table
    {
    return $table
    ->columns([
    Tables\Columns\TextColumn::make('name')
    ->label('Tên thị trường')
                    ->searchable(),

    Tables\Columns\TextColumn::make('address')
                    ->label('Địa chỉ')
        ->limit(50),

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
            'index' => Pages\ListMarkets::route('/'),
            'create' => Pages\CreateMarket::route('/create'),
            'view' => Pages\ViewMarket::route('/{record}'),
            'edit' => Pages\EditMarket::route('/{record}/edit'),
        ];
    }
}
