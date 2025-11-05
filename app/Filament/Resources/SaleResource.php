<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Mail\SaleCredentials;
use App\Models\Sale;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Nhân viên bán hàng';

    protected static ?string $modelLabel = 'Nhân viên bán hàng';

    protected static ?string $pluralModelLabel = 'Nhân viên bán hàng';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('Họ tên')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('phone')
                    ->label('Số điện thoại')
                    ->tel()
                    ->required()
                    ->maxLength(255),

                Textarea::make('address')
                    ->label('Địa chỉ')
                    ->required()
                    ->columnSpanFull(),

                Select::make('markets')
                    ->label('Chợ')
                    ->relationship('markets', 'name')
                    ->multiple()
                    ->preload()
                    ->required(),

                Toggle::make('active')
                    ->label('Kích hoạt')
                    ->default(true),

                TextInput::make('order')
                    ->label('Thứ tự')
                    ->numeric()
                    ->default(0),

                Textarea::make('notes')
                    ->label('Ghi chú')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Họ tên')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Số điện thoại'),

                Tables\Columns\TextColumn::make('markets.name')
                    ->label('Chợ')
                    ->listWithLineBreaks()
                    ->badge(),

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
            ])
            ->actions([
                EditAction::make(),
                Action::make('generate_password')
                    ->label('Tạo mật khẩu')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Tạo mật khẩu mới')
                    ->modalDescription('Hệ thống sẽ tạo mật khẩu ngẫu nhiên mới và gửi thông tin đăng nhập về email của nhân viên bán hàng.')
                    ->modalSubmitActionLabel('Tạo mật khẩu')
                    ->action(function (Sale $record) {
                        $newPassword = \Illuminate\Support\Str::random(12);
                        $record->update(['password' => Hash::make($newPassword)]);

                        // Send email with login credentials
                        try {
                            Mail::to($record->email)->send(new SaleCredentials($record, $newPassword));
                            \Filament\Notifications\Notification::make()
                                ->title('Credentials email sent successfully')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Password generated but email failed to send')
                                ->body('New password was set but email could not be sent: '.$e->getMessage())
                                ->warning()
                                ->send();
                        }
                    }),
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
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'view' => Pages\ViewSale::route('/{record}'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
