<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Exports\ReportExport;
use App\Filament\Resources\ReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Maatwebsite\Excel\Facades\Excel;

class ViewReport extends ViewRecord
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export_excel')
                ->label('Xuất Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $fileName = sprintf(
                        'bao-cao-%s-den-%s.xlsx',
                        $this->record->from_day->format('d-m-Y'),
                        $this->record->to_day->format('d-m-Y')
                    );

                    return Excel::download(new ReportExport($this->record), $fileName);
                }),

            Actions\EditAction::make(),
        ];
    }
}
