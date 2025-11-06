<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Filament\Resources\SurveyResource\Schemas\SurveyFormBuilder;
use App\Models\Survey;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
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
        return SurveyFormBuilder::make($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('market.name')
                    ->label('Chợ')
                    ->searchable(),

                Tables\Columns\TextColumn::make('sale.name')
                    ->label('Người khảo sát')
                    ->searchable(),

                Tables\Columns\TextColumn::make('survey_day')
                    ->label('Ngày khảo sát')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('survey_items_count')
                    ->counts('surveyItems')
                    ->label('Số sản phẩm')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                    ->label('Người khảo sát')
                    ->relationship('sale', 'name'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (DeleteAction $action, Survey $record): void {
                        // Check nếu Survey đang được sử dụng trong Report
                        if ($record->reportItems()->exists()) {
                            Notification::make()
                                ->title('Không thể xóa khảo sát')
                                ->body('Khảo sát này đang được sử dụng trong báo cáo. Vui lòng xóa các mục báo cáo liên quan trước.')
                                ->danger()
                                ->send();

                            $action->cancel();

                            return;
                        }

                        // Xóa tất cả SurveyItems trước khi xóa Survey (cascade delete)
                        $itemsCount = $record->surveyItems()->count();
                        $record->surveyItems()->delete();

                        if ($itemsCount > 0) {
                            Notification::make()
                                ->title('Đã xóa dữ liệu liên quan')
                                ->body("Đã xóa {$itemsCount} sản phẩm khảo sát cùng với khảo sát này.")
                                ->info()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function (DeleteBulkAction $action, $records): void {
                            // Check nếu có Survey đang được sử dụng trong Report
                            $surveysWithReportItems = collect($records)
                                ->filter(fn (Survey $survey) => $survey->reportItems()->exists());

                            if ($surveysWithReportItems->isNotEmpty()) {
                                $surveyDays = $surveysWithReportItems
                                    ->pluck('survey_day')
                                    ->map(fn ($date) => $date->format('d/m/Y'))
                                    ->join(', ');

                                Notification::make()
                                    ->title('Không thể xóa khảo sát')
                                    ->body($surveyDays
                                        ? "Các khảo sát ngày {$surveyDays} đang được sử dụng trong báo cáo. Vui lòng xử lý báo cáo trước."
                                        : 'Một số khảo sát đang được sử dụng trong báo cáo. Vui lòng xử lý báo cáo trước.')
                                    ->danger()
                                    ->send();

                                $action->cancel();

                                return;
                            }

                            // Xóa tất cả SurveyItems trước khi xóa Surveys (cascade delete)
                            $totalItemsDeleted = 0;

                            foreach ($records as $record) {
                                $totalItemsDeleted += $record->surveyItems()->count();
                                $record->surveyItems()->delete();
                            }

                            if ($totalItemsDeleted > 0) {
                                Notification::make()
                                    ->title('Đã xóa dữ liệu liên quan')
                                    ->body("Đã xóa {$totalItemsDeleted} sản phẩm khảo sát cùng với các khảo sát.")
                                    ->info()
                                    ->send();
                            }
                        }),
                ]),
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
            \App\Filament\Resources\SurveyResource\RelationManagers\SurveyItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveys::route('/'),
            'create' => Pages\CreateSurvey::route('/create'),
            'view' => Pages\ViewSurvey::route('/{record}'),
            'edit' => Pages\EditSurvey::route('/{record}/edit'),
        ];
    }
}
