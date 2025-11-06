<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Report;
use App\Models\Survey;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Báo cáo';

    protected static ?string $modelLabel = 'Báo cáo';

    protected static ?string $pluralModelLabel = 'Báo cáo';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Khoảng thời gian báo cáo')
                    ->schema([
                        Select::make('quick_range')
                            ->label('Chọn nhanh')
                            ->placeholder('Chọn khoảng thời gian hoặc nhập thủ công')
                            ->options([
                                'today' => 'Hôm nay',
                                'this_week' => 'Tuần này',
                                'this_month' => 'Tháng này',
                                'this_year' => 'Năm nay',
                                'last_month' => 'Tháng trước',
                                'last_year' => 'Năm trước',
                                'all' => 'Tất cả',
                            ])
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (! $state) {
                                    return;
                                }

                                $dates = match ($state) {
                                    'today' => [now(), now()],
                                    'this_week' => [now()->startOfWeek(), now()->endOfWeek()],
                                    'this_month' => [now()->startOfMonth(), now()->endOfMonth()],
                                    'this_year' => [now()->startOfYear(), now()->endOfYear()],
                                    'last_month' => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
                                    'last_year' => [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()],
                                    'all' => [Survey::min('survey_day'), Survey::max('survey_day')],
                                    default => [null, null],
                                };

                                $set('from_day', $dates[0]);
                                $set('to_day', $dates[1]);
                                $set('included_survey_ids', []);
                            })
                            ->native(false)
                            ->columnSpanFull(),

                        DatePicker::make('from_day')
                            ->label('Từ ngày')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('included_survey_ids', []);
                                $set('quick_range', null);
                            }),

                        DatePicker::make('to_day')
                            ->label('Đến ngày')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('included_survey_ids', []);
                                $set('quick_range', null);
                            })
                            ->afterOrEqual('from_day'),
                    ])
                    ->columns(2),

                Section::make('Chọn khảo sát')
                    ->schema([
                        CheckboxList::make('included_survey_ids')
                            ->label('Danh sách khảo sát')
                            ->options(function (callable $get): array {
                                $fromDay = $get('from_day');
                                $toDay = $get('to_day');

                                if (! $fromDay || ! $toDay) {
                                    return [];
                                }

                                return Survey::query()
                                    ->whereBetween('survey_day', [$fromDay, $toDay])
                                    ->with(['market', 'sale'])
                                    ->get()
                                    ->mapWithKeys(function (Survey $survey) {
                                        return [
                                            $survey->id => sprintf(
                                                '%s - %s - %s',
                                                $survey->survey_day->format('d/m/Y'),
                                                $survey->market->name ?? 'N/A',
                                                $survey->sale->name ?? 'N/A'
                                            ),
                                        ];
                                    })
                                    ->toArray();
                            })
                            ->live()
                            ->required()
                            ->searchable()
                            ->bulkToggleable()
                            ->helperText('Chọn các khảo sát muốn đưa vào báo cáo. Báo cáo sẽ tính trung bình giá của từng sản phẩm từ các khảo sát đã chọn.')
                            ->columnSpanFull(),

                        Placeholder::make('survey_count')
                            ->label('Số khảo sát đã chọn')
                            ->content(fn (callable $get): string => count($get('included_survey_ids') ?? []).' khảo sát')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('Thông tin bổ sung')
                    ->schema([
                        Toggle::make('active')
                            ->label('Kích hoạt')
                            ->default(true),

                        TextInput::make('order')
                            ->label('Thứ tự')
                            ->numeric()
                            ->default(0),

                        Placeholder::make('created_by')
                            ->label('Người tạo')
                            ->content(fn (?Report $record): string => $record?->createdByAdmin?->name ?? auth()->user()->name)
                            ->visibleOn('view'),

                        Placeholder::make('generated_at_display')
                            ->label('Thời gian tạo')
                            ->content(fn (?Report $record): string => $record?->generated_at?->format('d/m/Y H:i') ?? '-')
                            ->visibleOn('view'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('from_day')
                    ->label('Từ ngày')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('to_day')
                    ->label('Đến ngày')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('createdByAdmin.name')
                    ->label('Người tạo'),

                Tables\Columns\TextColumn::make('generated_at')
                    ->label('Thời gian tạo')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('report_items_count')
                    ->counts('reportItems')
                    ->label('Số dòng dữ liệu')
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
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Trạng thái kích hoạt'),
                Tables\Filters\Filter::make('date_range')
                    ->label('Khoảng thời gian')
                    ->form([
                        DatePicker::make('from_day')
                            ->label('Từ ngày'),
                        DatePicker::make('to_day')
                            ->label('Đến ngày'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from_day'], fn (Builder $query, $date): Builder => $query->where('from_day', '>=', $date))
                            ->when($data['to_day'], fn (Builder $query, $date): Builder => $query->where('to_day', '<=', $date));
                    }),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
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
            \App\Filament\Resources\ReportResource\RelationManagers\ReportItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'view' => Pages\ViewReport::route('/{record}'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}
