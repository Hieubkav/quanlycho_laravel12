<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Trang chủ';

    protected static ?string $title = 'Trang chủ';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_homepage')
                ->label('Xem trang chủ')
                ->icon('heroicon-o-globe-alt')
                ->url('/', shouldOpenInNewTab: true),

            Action::make('view_survey')
                ->label('Xem trang khảo sát')
                ->icon('heroicon-o-document-text')
                ->url('/khaosat', shouldOpenInNewTab: true),
        ];
    }
}
