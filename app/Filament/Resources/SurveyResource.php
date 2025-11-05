<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Models\Survey;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use BackedEnum;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Khảo sát';

    protected static ?string $modelLabel = 'Khảo sát';

    protected static ?string $pluralModelLabel = 'Khảo sát';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('market_id')
                    ->label('Chợ')
                    ->relationship('market', 'name')
                    ->required(),

                Select::make('sale_id')
                    ->label('Nhân viên bán hàng')
                    ->relationship('sale', 'name')
                    ->required(),

                DatePicker::make('survey_day')
                    ->label('Ngày khảo sát')
                    ->required(),

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
    Tables\Columns\TextColumn::make('market.name')
    ->label('Chợ')
    ->searchable(),

    Tables\Columns\TextColumn::make('sale.name')
    ->label('Nhân viên bán hàng')
    ->searchable(),

    Tables\Columns\TextColumn::make('survey_day')
    ->label('Ngày khảo sát')
    ->date()
                    ->sortable(),

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
        Tables\Filters\SelectFilter::make('market')
                    ->label('Chợ')
                    ->relationship('market', 'name'),
                Tables\Filters\SelectFilter::make('sale')
                    ->label('Nhân viên bán hàng')
                    ->relationship('sale', 'name'),
            ])
            ->actions([
                ViewAction::make(),
                // Không có Edit/Delete actions vì chỉ view
            ])
            ->bulkActions([
                // Không có bulk actions vì chỉ view
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();
                if ($user->role === 'sale') {
                    // Nếu là sale, chỉ xem surveys của mình
                    $query->where('sale_id', $user->id);
                }

                return $query;
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
            'index' => Pages\ListSurveys::route('/'),
            'view' => Pages\ViewSurvey::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
