<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

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
                    /** @var Product $record */
                    $record = $this->record;

                    if ($record->is_default && Auth::user()?->role !== 'admin') {
                        Notification::make()
                            ->title('Không thể xóa sản phẩm mặc định')
                            ->body('Vui lòng đăng nhập bằng tài khoản quản trị viên để xóa sản phẩm mặc định.')
                            ->danger()
                            ->send();

                        $action->cancel();

                        return;
                    }

                    if ($record->surveyItems()->exists()) {
                        Notification::make()
                            ->title('Không thể xóa sản phẩm')
                            ->body('Sản phẩm đang được dùng trong các khảo sát. Hãy xử lý khảo sát liên quan trước khi xóa.')
                            ->danger()
                            ->send();

                        $action->cancel();
                    }
                })
                ->visible(fn (Product $record): bool => ! ($record->is_default && Auth::user()?->role !== 'admin')),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
