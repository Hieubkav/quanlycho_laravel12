<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class Settings extends Page
{
    use InteractsWithForms;

    public function getView(): string
    {
        return 'filament.pages.settings';
    }

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationLabel = 'Cài đặt';

    protected static ?string $title = 'Cài đặt hệ thống';

    protected static ?int $navigationSort = 99;

    public function mount(): void
    {
        $setting = Setting::where('key', 'global')->first();

        $this->form->fill($setting ? $setting->toArray() : []);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin cơ bản')
                    ->schema([
                        TextInput::make('brand_name')
                            ->label('Tên thương hiệu')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('logo_url')
                            ->label('URL Logo')
                            ->url(),

                        TextInput::make('favicon_url')
                            ->label('URL Favicon')
                            ->url(),

                        Toggle::make('active')
                            ->label('Kích hoạt')
                            ->default(true),

                        TextInput::make('order')
                            ->label('Thứ tự')
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Lưu thay đổi')
                ->action('save')
                ->color('primary'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::updateOrCreate(
            ['key' => 'global'],
            $data
        );

        Notification::make()
            ->title('Cài đặt đã được lưu thành công')
            ->success()
            ->send();
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->role === 'admin';
    }
}
