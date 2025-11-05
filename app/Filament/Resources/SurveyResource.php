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
                    ->relationship('market', 'name')
                    ->required(),

                Select::make('sale_id')
                    ->relationship('sale', 'name')
                    ->required(),

                DatePicker::make('survey_day')
                    ->required(),

                Textarea::make('notes')
                    ->columnSpanFull(),

                Toggle::make('active')
                    ->label('Active')
                    ->default(true),

                TextInput::make('order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('market.name')
                    ->label('Market')
                    ->searchable(),

                Tables\Columns\TextColumn::make('sale.name')
                    ->label('Sale')
                    ->searchable(),

                Tables\Columns\TextColumn::make('survey_day')
                    ->date()
                    ->sortable(),

                Tables\Columns\IconColumn::make('active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Active Status'),
                Tables\Filters\SelectFilter::make('market')
                    ->relationship('market', 'name'),
                Tables\Filters\SelectFilter::make('sale')
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
