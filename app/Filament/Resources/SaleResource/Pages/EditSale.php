<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSale extends EditRecord
{
    protected static string $resource = SaleResource::class;

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
                    if ($this->record->surveys()->exists()) {
                        Notification::make()
                            ->title('Không thể xóa nhân viên bán hàng')
                            ->body('Nhân viên này đang có khảo sát liên quan. Vui lòng xóa hoặc chuyển khảo sát trước khi tiếp tục.')
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
