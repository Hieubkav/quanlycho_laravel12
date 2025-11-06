<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Sản phẩm';

    protected static ?string $modelLabel = 'Sản phẩm';

    protected static ?string $pluralModelLabel = 'Sản phẩm';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Forms\Components\TextInput::make('name')
                    ->label('Tên sản phẩm')
                    ->required()
                    ->maxLength(255),

                \Filament\Forms\Components\Select::make('unit_id')
                    ->label('Đơn vị')
                    ->relationship('unit', 'name')
                    ->required(),

                \Filament\Forms\Components\Toggle::make('is_default')
                    ->label('Là sản phẩm mặc định')
                    ->default(false),

                \Filament\Forms\Components\Textarea::make('notes')
                    ->label('Ghi chú')
                    ->columnSpanFull(),

                \Filament\Forms\Components\Toggle::make('active')
                    ->label('Kích hoạt')
                    ->default(true),

                \Filament\Forms\Components\TextInput::make('order')
                    ->label('Thứ tự')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên sản phẩm')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('unit.name')
                    ->label('Đơn vị'),

                Tables\Columns\TextColumn::make('survey_items_count')
                    ->counts('surveyItems')
                    ->label('Số lần khảo sát')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_default')
                    ->label('Mặc định')
                    ->boolean(),

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
                Tables\Filters\TernaryFilter::make('is_default')
                    ->label('Sản phẩm mặc định'),
            ])
            ->actions([
                EditAction::make()
                    ->visible(fn (Product $record): bool => ! ($record->is_default && Auth::user()->role !== 'admin')),
                DeleteAction::make()
                    ->before(function (Product $record) {
                        if ($record->is_default && Auth::user()->role !== 'admin') {
                            \Filament\Notifications\Notification::make()
                                ->title('Không thể xóa')
                                ->body('Không thể xóa sản phẩm mặc định. Chỉ Admin mới có quyền xóa sản phẩm mặc định.')
                                ->danger()
                                ->send();

                            return false;
                        }

                        if (\App\Models\SurveyItem::where('product_id', $record->id)->exists()) {
                            \Filament\Notifications\Notification::make()
                                ->title('Không thể xóa')
                                ->body('Không thể xóa sản phẩm này vì đã được sử dụng trong các khảo sát. Hãy xóa các khảo sát liên quan trước.')
                                ->danger()
                                ->send();

                            return false;
                        }

                        return true;
                    })
                    ->action(function (Product $record) {
                        try {
                            $record->delete();
                            \Filament\Notifications\Notification::make()
                                ->title('Đã xóa thành công')
                                ->body('Sản phẩm đã được xóa.')
                                ->success()
                                ->send();
                        } catch (\Illuminate\Database\QueryException $e) {
                            if ($e->getCode() === '23000') {
                                \Filament\Notifications\Notification::make()
                                    ->title('Không thể xóa')
                                    ->body('Không thể xóa sản phẩm này vì đã được sử dụng trong các khảo sát. Hãy xóa các khảo sát liên quan trước.')
                                    ->danger()
                                    ->send();
                            } else {
                                throw $e;
                            }
                        }
                    })
                    ->visible(fn (Product $record): bool => ! ($record->is_default && Auth::user()->role !== 'admin')),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function ($records) {
                            $restrictedRecords = $records->filter(function ($record) {
                                return $record->is_default && Auth::user()->role !== 'admin';
                            });

                            $usedRecords = $records->filter(function ($record) {
                                return \App\Models\SurveyItem::where('product_id', $record->id)->exists();
                            });

                            if ($restrictedRecords->isNotEmpty() || $usedRecords->isNotEmpty()) {
                                $messages = [];

                                if ($restrictedRecords->isNotEmpty()) {
                                    $messages[] = 'Không thể xóa sản phẩm mặc định. Chỉ Admin mới có quyền xóa sản phẩm mặc định.';
                                }

                                if ($usedRecords->isNotEmpty()) {
                                    $messages[] = 'Không thể xóa sản phẩm đã được sử dụng trong các khảo sát. Hãy xóa các khảo sát liên quan trước.';
                                }

                                \Filament\Notifications\Notification::make()
                                    ->title('Không thể xóa một số sản phẩm')
                                    ->body(implode(' ', $messages))
                                    ->danger()
                                    ->send();

                                return false;
                            }
                        })
                        ->action(function ($records) {
                            $deletedCount = 0;
                            $failedCount = 0;

                            foreach ($records as $record) {
                                try {
                                    $record->delete();
                                    $deletedCount++;
                                } catch (\Illuminate\Database\QueryException $e) {
                                    if ($e->getCode() === '23000') {
                                        $failedCount++;
                                    } else {
                                        throw $e;
                                    }
                                }
                            }

                            if ($deletedCount > 0) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Đã xóa thành công')
                                    ->body("Đã xóa {$deletedCount} sản phẩm".($failedCount > 0 ? ". {$failedCount} sản phẩm không thể xóa vì đang được sử dụng." : '.'))
                                    ->success()
                                    ->send();
                            }

                            if ($failedCount > 0 && $deletedCount === 0) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Không thể xóa')
                                    ->body('Không thể xóa các sản phẩm đã chọn vì đang được sử dụng trong các khảo sát.')
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->visible(fn (): bool => Auth::user()->role === 'admin'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
