<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditReport extends EditRecord
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Lưu thay đổi')
                ->submit('save')
                ->keyBindings(['mod+s']),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->before(function (Actions\DeleteAction $action): void {
                    if ($this->record->reportItems()->exists()) {
                        Notification::make()
                            ->title('Không thể xóa báo cáo')
                            ->body('Báo cáo vẫn còn dữ liệu chi tiết. Hãy xóa hoặc di chuyển các dòng báo cáo trước.')
                            ->danger()
                            ->send();

                        $action->cancel();
                    }
                }),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
