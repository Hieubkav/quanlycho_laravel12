<?php

namespace App\Filament\Khaosat\Pages;

use App\Models\Sale;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Trang chủ';

    public function getHeading(): string
    {
        /** @var Sale $sale */
        $sale = auth()->guard('sale')->user();

        return 'Xin chào, '.$sale->name.'!';
    }

    public function getSubheading(): ?string
    {
        return 'Chào mừng bạn đến với hệ thống khảo sát chợ';
    }

    public function getView(): string
    {
        return 'filament.khaosat.pages.dashboard';
    }
}
