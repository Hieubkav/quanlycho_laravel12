<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Mail\AdminPasswordReset;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Admins';

    protected static ?string $modelLabel = 'Admin';

    protected static ?string $pluralModelLabel = 'Admins';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Schemas\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Schemas\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),

                Schemas\Components\Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                    ])
                    ->required()
                    ->default('admin'),

                Schemas\Components\Toggle::make('active')
                    ->label('Active')
                    ->default(true),

                Schemas\Components\TextInput::make('order')
                    ->numeric()
                    ->default(0),

                Schemas\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone'),

                Tables\Columns\TextColumn::make('role'),

                Tables\Columns\IconColumn::make('active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Active Status'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('reset_password')
                    ->label('Reset Password')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Reset Password')
                    ->modalDescription('This will generate a new random password and send it to the admin via email.')
                    ->modalSubmitActionLabel('Reset Password')
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
