<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use BackedEnum;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Cài đặt';

    protected static ?string $modelLabel = 'Cài đặt';

    protected static ?string $pluralModelLabel = 'Cài đặt';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('brand_name')
                    ->label('Tên thương hiệu')
                    ->required()
                    ->maxLength(255),

                TextInput::make('logo_url')
                    ->label('URL Logo')
                    ->url(),

                TextInput::make('favicon_url')
                    ->label('URL Favicon')
                    ->url(),

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
    Tables\Columns\TextColumn::make('brand_name')
    ->label('Tên thương hiệu')
                    ->searchable(),

                Tables\Columns\TextColumn::make('logo_url')
        ->label('URL Logo'),

                Tables\Columns\IconColumn::make('active')
        ->label('Kích hoạt')
    ->boolean(),

        Tables\Columns\TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                //
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('key', 'global');
            });
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
            'index' => Pages\ListSettings::route('/'),
            'view' => Pages\ViewSetting::route('/{record}'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->role === 'admin';
    }

    public static function canView($record): bool
    {
        return Auth::user()?->role === 'admin';
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->role === 'admin';
    }
}
