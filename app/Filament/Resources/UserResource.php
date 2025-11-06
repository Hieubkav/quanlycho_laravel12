<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Mail\AdminPasswordReset;
use App\Models\User;
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

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Quản trị viên';

    protected static ?string $modelLabel = 'Quản trị viên';

    protected static ?string $pluralModelLabel = 'Quản trị viên';

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
                    ->maxLength(255),

                TextInput::make('phone')
                    ->label('Số điện thoại')
                    ->tel()
                    ->maxLength(255),

                Select::make('role')
                    ->label('Vai trò')
                    ->options([
                        'admin' => 'Quản trị viên',
                    ])
                    ->required()
                    ->default('admin'),

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

                Tables\Columns\TextColumn::make('role')
                    ->label('Vai trò'),

                Tables\Columns\TextColumn::make('reports_count')
                    ->counts('reports')
                    ->label('Số báo cáo')
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
            ])
            ->actions([
                EditAction::make(),
                Action::make('reset_password')
                    ->label('Đặt lại mật khẩu')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Đặt lại mật khẩu')
                    ->modalDescription('Hệ thống sẽ tạo mật khẩu ngẫu nhiên mới và gửi về email của quản trị viên.')
                    ->modalSubmitActionLabel('Đặt lại mật khẩu')
                    ->action(function (User $record) {
                        $newPassword = \Illuminate\Support\Str::random(12);
                        $record->update(['password' => Hash::make($newPassword)]);

                        // Send email with new password
                        try {
                            Mail::to($record->email)->send(new AdminPasswordReset($record, $newPassword));
                            \Filament\Notifications\Notification::make()
                                ->title('Password reset email sent successfully')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Password reset but email failed to send')
                                ->body('Password was reset but email could not be sent: '.$e->getMessage())
                                ->warning()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function (DeleteBulkAction $action, $records): void {
                            $usersWithReports = collect($records)
                                ->filter(fn (User $user) => $user->reports()->exists());

                            if ($usersWithReports->isEmpty()) {
                                return;
                            }

                            $names = $usersWithReports->pluck('name')->filter()->join(', ');

                            \Filament\Notifications\Notification::make()
                                ->title('Không thể xóa quản trị viên')
                                ->body($names
                                    ? "Các tài khoản sau đã tạo báo cáo: {$names}. Vui lòng chuyển hoặc xóa báo cáo trước khi xóa."
                                    : 'Một số tài khoản đã tạo báo cáo. Vui lòng xử lý báo cáo trước khi xóa.')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Không cho phép tạo user mới
    }
}
