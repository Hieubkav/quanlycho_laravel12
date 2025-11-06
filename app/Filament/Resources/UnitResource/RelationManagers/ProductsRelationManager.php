<?php

namespace App\Filament\Resources\UnitResource\RelationManagers;

use App\Filament\Resources\ProductResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $title = 'Sản phẩm';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Tên sản phẩm')
                    ->required()
                    ->maxLength(255),

                Toggle::make('is_default')
                    ->label('Là sản phẩm mặc định')
                    ->default(false),

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

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên sản phẩm')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_default')
                    ->label('Mặc định')
                    ->boolean(),

                IconColumn::make('active')
                    ->label('Kích hoạt')
                    ->boolean(),

                TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order', 'asc')
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['unit_id'] = $this->getOwnerRecord()->id;

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('view')
                    ->label('Xem chi tiết')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record): string => ProductResource::getUrl('view', ['record' => $record]))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
