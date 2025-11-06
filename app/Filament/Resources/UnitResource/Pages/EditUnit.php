<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUnit extends EditRecord
{
    protected static string $resource = UnitResource::class;

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
                    if ($this->record->products()->exists()) {
                        Notification::make()
                            ->title('Không thể xóa đơn vị')
                            ->body('Đơn vị này đang được sử dụng bởi các sản phẩm. Vui lòng xóa các sản phẩm liên quan trước.')
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
