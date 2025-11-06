<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use App\Models\Report;
use App\Models\ReportItem;
use App\Models\SurveyItem;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateReport extends CreateRecord
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Tạo báo cáo'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getCreateFormAction(): Actions\Action
    {
        return parent::getCreateFormAction()
            ->submit(null)
            ->action('create');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by_admin_id'] = auth()->id();
        $data['generated_at'] = now();

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            // Tạo Report record
            $report = Report::create($data);

            // Lấy danh sách survey IDs đã chọn
            $surveyIds = $data['included_survey_ids'] ?? [];

            if (empty($surveyIds)) {
                Notification::make()
                    ->title('Chưa chọn khảo sát')
                    ->body('Vui lòng chọn ít nhất một khảo sát để tạo báo cáo.')
                    ->warning()
                    ->send();

                return $report;
            }

            // Lấy tất cả SurveyItems từ các surveys đã chọn
            $surveyItems = SurveyItem::query()
                ->whereIn('survey_id', $surveyIds)
                ->with(['product', 'survey'])
                ->get();

            if ($surveyItems->isEmpty()) {
                Notification::make()
                    ->title('Không có dữ liệu')
                    ->body('Các khảo sát đã chọn không có dữ liệu sản phẩm.')
                    ->warning()
                    ->send();

                return $report;
            }

            // Nhóm theo product_id và tính trung bình giá
            $productAverages = $surveyItems->groupBy('product_id')->map(function ($items) {
                return [
                    'product_id' => $items->first()->product_id,
                    'average_price' => $items->avg('price'),
                    'count' => $items->count(),
                    'survey_ids' => $items->pluck('survey_id')->unique()->values(),
                ];
            });

            // Tạo ReportItems với giá trung bình
            foreach ($productAverages as $productData) {
                // Lấy survey_id đầu tiên làm đại diện (để tham chiếu)
                $representativeSurveyId = $productData['survey_ids']->first();

                ReportItem::create([
                    'report_id' => $report->id,
                    'survey_id' => $representativeSurveyId,
                    'product_id' => $productData['product_id'],
                    'price' => round($productData['average_price'], 2),
                    'notes' => sprintf(
                        'Trung bình từ %d khảo sát (Survey IDs: %s)',
                        $productData['count'],
                        $productData['survey_ids']->implode(', ')
                    ),
                    'active' => true,
                    'order' => 0,
                ]);
            }

            Notification::make()
                ->title('Tạo báo cáo thành công')
                ->body(sprintf(
                    'Đã tạo báo cáo với %d sản phẩm từ %d khảo sát.',
                    $productAverages->count(),
                    count($surveyIds)
                ))
                ->success()
                ->send();

            return $report;
        });
    }
}
