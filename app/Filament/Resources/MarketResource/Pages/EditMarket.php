<?php

namespace App\Filament\Resources\MarketResource\Pages;

use App\Filament\Resources\MarketResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditMarket extends EditRecord
{
    protected static string $resource = MarketResource::class;

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
                            ->title('Không thể xóa chợ')
                            ->body('Chợ này đang có khảo sát liên quan. Vui lòng xóa hoặc điều chỉnh các khảo sát đó trước.')
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
