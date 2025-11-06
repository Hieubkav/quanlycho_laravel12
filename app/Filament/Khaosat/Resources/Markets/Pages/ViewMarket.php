<?php

namespace App\Filament\Khaosat\Resources\Markets\Pages;

use App\Filament\Khaosat\Resources\Markets\MarketResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMarket extends ViewRecord
{
    protected static string $resource = MarketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
