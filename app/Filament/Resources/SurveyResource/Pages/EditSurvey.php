<?php

namespace App\Filament\Resources\SurveyResource\Pages;

use App\Filament\Resources\SurveyResource;
use App\Models\SurveyItem;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSurvey extends EditRecord
{
    protected static string $resource = SurveyResource::class;

    protected array $cachedPrices = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Lưu thay đổi')
                ->submit('save')
                ->keyBindings(['mod+s']),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->label('Xóa')
                ->requiresConfirmation()
                ->modalHeading('Xóa khảo sát')
                ->modalDescription('Bạn có chắc chắn muốn xóa khảo sát này? Hành động này không thể hoàn tác.')
                ->modalSubmitActionLabel('Xóa')
                ->successNotificationTitle('Khảo sát đã được xóa'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $surveyItems = $this->record->surveyItems()->get();

        $prices = [];
        foreach ($surveyItems as $item) {
            $prices[$item->product_id] = [
                'price' => $item->price,
                'notes' => $item->notes,
                'product_id' => $item->product_id,
            ];
        }

        $data['prices'] = $prices;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $prices = $data['prices'] ?? [];
        unset($data['prices']);

        $this->cachedPrices = $prices;

        return $data;
    }

    protected function afterSave(): void
    {
        if (isset($this->cachedPrices)) {
            $this->record->surveyItems()->delete();

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
            ->title('Cập nhật thành công!')
            ->body('Khảo sát đã được cập nhật.')
            ->send();
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return null;
    }
}
