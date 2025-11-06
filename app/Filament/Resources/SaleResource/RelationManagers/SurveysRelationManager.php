<?php

namespace App\Filament\Resources\SaleResource\RelationManagers;

use App\Filament\Resources\SurveyResource;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SurveysRelationManager extends RelationManager
{
    protected static string $relationship = 'surveys';

    protected static ?string $title = 'Khảo sát';

    protected static ?string $recordTitleAttribute = 'survey_day';

    protected static ?string $name = 'sale-surveys';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('survey_day')
                    ->label('Ngày khảo sát')
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('survey_day')
                    ->label('Ngày khảo sát')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('market.name')
                    ->label('Chợ')
                    ->searchable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('notes')
                    ->label('Ghi chú')
                    ->limit(50)
                    ->toggleable(),

                IconColumn::make('active')
                    ->label('Kích hoạt')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('survey_day', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                // Surveys are managed from SurveyResource
            ])
            ->recordActions([
                Action::make('view')
                    ->label('Xem chi tiết')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => SurveyResource::getUrl('view', ['record' => $record])),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
