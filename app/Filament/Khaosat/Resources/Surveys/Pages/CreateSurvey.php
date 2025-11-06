<?php

namespace App\Filament\Khaosat\Resources\Surveys\Pages;

use App\Filament\Khaosat\Resources\Surveys\SurveyResource;
use App\Models\SurveyItem;
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
        $prices = $data['prices'] ?? [];
        unset($data['prices']);

        $data['active'] = true;
        $data['order'] = 0;

        // Data is already in the correct format for afterCreate
        $this->cachedPrices = $prices;

        return $data;
    }

    protected function afterCreate(): void
    {
        if (isset($this->cachedPrices)) {
            foreach ($this->cachedPrices as $productId => $priceData) {
                if (isset($priceData['price']) && is_numeric($priceData['price']) && $priceData['price'] > 0) {
                    SurveyItem::create([
                        'survey_id' => $this->record->id,
                        'product_id' => $productId,
                        'price' => $priceData['price'],
                        'notes' => $priceData['notes'] ?? null,
                        'active' => true,
                        'order' => 0,
                    ]);
                }
            }
        }

        Notification::make()
            ->success()
            ->title('Tạo khảo sát thành công!')
            ->body('Bạn đã tạo khảo sát cho chợ '.$this->record->market->name.' vào ngày '.$this->record->survey_day->format('d/m/Y'))
            ->send();
    }

    protected array $cachedPrices = [];
}
