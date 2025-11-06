<?php

namespace App\Filament\Khaosat\Resources\Markets\Pages;

use App\Filament\Khaosat\Resources\Markets\MarketResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMarkets extends ListRecords
{
    protected static string $resource = MarketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
