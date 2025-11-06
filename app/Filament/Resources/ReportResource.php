<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Report;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
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
                DatePicker::make('from_day')
                    ->label('Từ ngày')
                    ->required(),

                DatePicker::make('to_day')
                    ->label('Đến ngày')
                    ->required(),

                Textarea::make('summary_rows')
                    ->label('Tóm tắt báo cáo')
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
            // Có thể thêm action để generate report
        ])
            ->bulkActions([
            BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function (DeleteBulkAction $action, $records): void {
                            $blockedReports = collect($records)
                                ->filter(fn (Report $report) => $report->reportItems()->exists());

                            if ($blockedReports->isEmpty()) {
                                return;
                            }

                            $titles = $blockedReports
                                ->map(fn (Report $report) => sprintf('#%d', $report->getKey()))
                                ->join(', ');

                            Notification::make()
                                ->title('Không thể xóa báo cáo')
                                ->body("Các báo cáo {$titles} vẫn còn dữ liệu chi tiết. Vui lòng xóa chi tiết trước.")
                                ->danger()
                                ->send();

                            $action->cancel();
                        }),
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
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'view' => Pages\ViewReport::route('/{record}'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}
