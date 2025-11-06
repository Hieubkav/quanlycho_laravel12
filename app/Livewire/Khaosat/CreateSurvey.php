<?php

namespace App\Livewire\Khaosat;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Survey;
use App\Models\SurveyItem;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class CreateSurvey extends Component
{
    public $surveyDate = '';

    public $selectedMarket = '';

    public $currentSurvey = null;

    public $prices = [];

    protected function rules(): array
    {
        return [
            'surveyDate' => 'required|date',
            'selectedMarket' => 'required|exists:markets,id',
            'prices.*' => 'nullable|numeric|min:0',
        ];
    }

    public function mount(): void
    {
        $firstMarket = $this->markets->first();

        if (! $firstMarket) {
            session()->flash('error', 'Bạn chưa được phân công vào chợ nào. Vui lòng liên hệ quản trị viên.');

            return;
        }

        $this->surveyDate = now()->format('Y-m-d');
        $this->selectedMarket = (string) $firstMarket->id;
    }

    public function createSurvey(): void
    {
        $this->validate([
            'surveyDate' => 'required|date',
            'selectedMarket' => 'required|exists:markets,id',
        ]);

        /** @var Sale $sale */
        $sale = Auth::guard('sale')->user();

        if (! $sale->markets()->where('markets.id', $this->selectedMarket)->exists()) {
            $this->addError('selectedMarket', 'Bạn không được phép tạo khảo sát cho chợ này.');

            return;
        }

        $existingSurvey = Survey::where('sale_id', $sale->id)
            ->where('market_id', $this->selectedMarket)
            ->where('survey_day', $this->surveyDate)
            ->first();

        if ($existingSurvey) {
            $this->addError('surveyDate', 'Bạn đã tạo khảo sát cho chợ này trong ngày này rồi.');

            return;
        }

        $this->currentSurvey = Survey::create([
            'sale_id' => $sale->id,
            'market_id' => $this->selectedMarket,
            'survey_day' => $this->surveyDate,
            'notes' => 'Created via web interface',
        ]);

        $this->loadProducts();

        session()->flash('message', 'Khảo sát đã được tạo thành công! Bây giờ bạn có thể nhập giá sản phẩm.');
    }

    public function loadProducts(): void
    {
        $products = Product::where('active', true)
            ->where('is_default', true)
            ->orderBy('order')
            ->get();

        $this->prices = [];

        foreach ($products as $product) {
            $this->prices[$product->id] = null;
        }
    }

    public function savePrices(): void
    {
        if (! $this->currentSurvey) {
            $this->addError('prices', 'Không tìm thấy khảo sát để lưu giá.');

            return;
        }

        $this->prices = collect($this->prices)
            ->map(fn ($price) => $price === '' ? null : $price)
            ->toArray();

        $this->validate(['prices.*' => 'nullable|numeric|min:0']);

        $hasAtLeastOnePrice = collect($this->prices)
            ->contains(fn ($price) => is_numeric($price) && (float) $price > 0);

        if (! $hasAtLeastOnePrice) {
            $this->addError('prices', 'Vui lòng điền ít nhất một giá sản phẩm.');

            return;
        }

        foreach ($this->prices as $productId => $price) {
            if (! is_numeric($price) || (float) $price <= 0) {
                continue;
            }

            SurveyItem::updateOrCreate(
                [
                    'survey_id' => $this->currentSurvey->id,
                    'product_id' => $productId,
                ],
                [
                    'price' => $price,
                    'notes' => '',
                ],
            );
        }

        $this->currentSurvey = null;
        $this->prices = [];

        session()->flash('message', 'Giá sản phẩm đã được lưu thành công!');
        $this->redirect(route('khaosat'));
    }

    public function cancel(): void
    {
        $this->redirect(route('khaosat'));
    }

    public function getMarketsProperty(): SupportCollection
    {
        if (! Auth::guard('sale')->check()) {
            return collect();
        }

        /** @var Sale $user */
        $user = Auth::guard('sale')->user();

        return $user->markets()
            ->where('active', true)
            ->orderBy('order')
            ->get();
    }

    public function getProductsProperty(): EloquentCollection
    {
        return Product::where('active', true)
            ->where('is_default', true)
            ->orderBy('order')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.khaosat.create-survey');
    }
}
