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
use Filament\Schemas\Components\Actions as ActionsComponent;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class Settings extends Page
{
    use InteractsWithForms;

    public ?array $data = [];

    private const FORM_FIELDS = [
        'brand_name',
        'logo_url',
        'favicon_url',
        'active',
        'order',
    ];

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationLabel = 'Cài đặt';

    protected static ?string $title = 'Cài đặt hệ thống';

    protected static ?int $navigationSort = 99;

    public function mount(): void
    {
        $setting = Setting::where('key', 'global')->first();

        $this->data = $setting
            ? $setting->only(self::FORM_FIELDS)
            : [
                'brand_name' => '',
                'logo_url' => '',
                'favicon_url' => '',
                'active' => true,
                'order' => 0,
            ];
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin cơ bản')
                    ->schema([
                        TextInput::make('brand_name')
                            ->label('Tên thương hiệu')
                            ->default('')
                            ->maxLength(255)
                            ->live()
                            ->statePath('data.brand_name'),

                        TextInput::make('logo_url')
                            ->label('URL Logo')
                            ->default('')
                            ->url()
                            ->live()
                            ->statePath('data.logo_url'),

                        TextInput::make('favicon_url')
                            ->label('URL Favicon')
                            ->default('')
                            ->url()
                            ->live()
                            ->statePath('data.favicon_url'),

                        Toggle::make('active')
                            ->label('Kích hoạt')
                            ->default(true)
                            ->live()
                            ->statePath('data.active'),

                        TextInput::make('order')
                            ->label('Thứ tự')
                            ->numeric()
                            ->default(0)
                            ->live()
                            ->statePath('data.order'),
                    ]),
                ActionsComponent::make($this->getFormActions())
                    ->alignment($this->getFormActionsAlignment())
                    ->sticky($this->areFormActionsSticky())
                    ->key('form-actions'),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->action('save')
                ->keyBindings(['mod+s']),
        ];
    }

    public function save(): void
    {
        Setting::updateOrCreate(
            ['key' => 'global'],
            $this->data
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
