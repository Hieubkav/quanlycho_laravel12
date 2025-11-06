<?php

namespace App\Filament\Khaosat\Resources\Surveys\Pages;

use App\Filament\Khaosat\Resources\Surveys\SurveyResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateSurvey extends CreateRecord
{
    protected static string $resource = SurveyResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Khảo sát đã được tạo thành công';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['active'] = true;
        $data['order'] = 0;

        return $data;
    }

    protected function afterCreate(): void
    {
        Notification::make()
            ->success()
            ->title('Tạo khảo sát thành công!')
            ->body('Bạn đã tạo khảo sát cho chợ '.$this->record->market->name.' vào ngày '.$this->record->survey_day->format('d/m/Y'))
            ->send();
    }
}
