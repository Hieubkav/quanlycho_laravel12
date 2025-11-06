<?php

namespace App\Filament\Resources\SurveyResource\Pages;

use App\Filament\Resources\SurveyResource;
use App\Models\SurveyItem;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateSurvey extends CreateRecord
{
    protected static string $resource = SurveyResource::class;

    protected array $cachedPrices = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create')
                ->label('Tạo mới')
                ->submit('create')
                ->keyBindings(['mod+s']),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $prices = $data['prices'] ?? [];
        unset($data['prices']);

        $data['active'] = $data['active'] ?? true;
        $data['order'] = $data['order'] ?? 0;

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
            ->body('Đã tạo khảo sát cho chợ '.$this->record->market->name.' vào ngày '.$this->record->survey_day->format('d/m/Y'))
            ->send();
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return null;
    }
}
