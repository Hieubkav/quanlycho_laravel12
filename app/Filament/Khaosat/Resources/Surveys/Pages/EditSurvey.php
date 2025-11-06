<?php

namespace App\Filament\Khaosat\Resources\Surveys\Pages;

use App\Filament\Khaosat\Resources\Surveys\SurveyResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSurvey extends EditRecord
{
    protected static string $resource = SurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Xóa')
                ->requiresConfirmation()
                ->modalHeading('Xóa khảo sát')
                ->modalDescription('Bạn có chắc chắn muốn xóa khảo sát này? Hành động này không thể hoàn tác.')
                ->modalSubmitActionLabel('Xóa')
                ->successNotificationTitle('Khảo sát đã được xóa'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Khảo sát đã được cập nhật';
    }

    protected function afterSave(): void
    {
        Notification::make()
            ->success()
            ->title('Cập nhật thành công!')
            ->body('Khảo sát của bạn đã được cập nhật.')
            ->send();
    }
}
