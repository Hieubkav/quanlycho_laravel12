<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Report;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use BackedEnum;
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
                    ->required(),

                DatePicker::make('to_day')
                    ->required(),

                Textarea::make('summary_rows')
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
                Tables\Columns\TextColumn::make('from_day')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('to_day')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('createdByAdmin.name')
                    ->label('Created By'),

                Tables\Columns\TextColumn::make('generated_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\IconColumn::make('active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Active Status'),
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        DatePicker::make('from_day'),
                        DatePicker::make('to_day'),
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
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'view' => Pages\ViewReport::route('/{record}'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}
