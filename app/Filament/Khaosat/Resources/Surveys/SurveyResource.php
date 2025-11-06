<?php

namespace App\Filament\Khaosat\Resources\Surveys;

use App\Filament\Khaosat\Resources\Surveys\Pages\CreateSurvey;
use App\Filament\Khaosat\Resources\Surveys\Pages\EditSurvey;
use App\Filament\Khaosat\Resources\Surveys\Pages\ListSurveys;
use App\Filament\Khaosat\Resources\Surveys\Schemas\SurveyForm;
use App\Filament\Khaosat\Resources\Surveys\Tables\SurveysTable;
use App\Models\Survey;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Khảo Sát';

    protected static ?string $modelLabel = 'khảo sát';

    protected static ?string $pluralModelLabel = 'khảo sát';

    public static function form(Schema $schema): Schema
    {
        return SurveyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SurveysTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('sale_id', auth()->guard('sale')->id())
            ->with(['market', 'surveyItems.product.unit'])
            ->latest('survey_day');
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
            'index' => ListSurveys::route('/'),
            'create' => CreateSurvey::route('/create'),
            'view' => Pages\ViewSurvey::route('/{record}'),
            'edit' => EditSurvey::route('/{record}/edit'),
        ];
    }
}
