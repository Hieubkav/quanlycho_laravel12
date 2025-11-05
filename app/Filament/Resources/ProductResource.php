<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Sản phẩm';

    protected static ?string $modelLabel = 'Sản phẩm';

    protected static ?string $pluralModelLabel = 'Sản phẩm';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Forms\Components\TextInput::make('name')
                    ->label('Tên sản phẩm')
                    ->required()
                    ->maxLength(255),

                \Filament\Forms\Components\Select::make('unit_id')
                    ->label('Đơn vị')
                    ->relationship('unit', 'name')
                    ->required(),

                \Filament\Forms\Components\Toggle::make('is_default')
                    ->label('Là sản phẩm mặc định')
                    ->default(false),

                \Filament\Forms\Components\Textarea::make('notes')
                    ->label('Ghi chú')
                    ->columnSpanFull(),

                \Filament\Forms\Components\Toggle::make('active')
                    ->label('Kích hoạt')
                    ->default(true),

                \Filament\Forms\Components\TextInput::make('order')
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
                    ->label('Tên sản phẩm')
                    ->searchable(),

                Tables\Columns\TextColumn::make('unit.name')
                    ->label('Đơn vị'),

                Tables\Columns\IconColumn::make('is_default')
                    ->label('Mặc định')
                    ->boolean(),

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
                Tables\Filters\TernaryFilter::make('is_default')
                    ->label('Sản phẩm mặc định'),
            ])
            ->actions([
                EditAction::make()
                    ->visible(fn (Product $record): bool => ! ($record->is_default && Auth::user()->role !== 'admin')),
                DeleteAction::make()
                    ->visible(fn (Product $record): bool => ! ($record->is_default && Auth::user()->role !== 'admin')),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn (): bool => Auth::user()->role === 'admin'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
