<?php

namespace App\Livewire\Khaosat;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Survey;
use App\Models\SurveyItem;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public $showSurveyForm = false;

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
        // Guarded by auth middleware
    }

    public function startSurvey(): void
    {
        $firstMarket = $this->markets->first();

        if (! $firstMarket) {
            session()->flash('message', 'Bạn chưa được phân công vào chợ nào. Vui lòng liên hệ quản trị viên.');

            return;
        }

        $this->showSurveyForm = true;
        $this->surveyDate = now()->format('Y-m-d');
        $this->selectedMarket = (string) $firstMarket->id;
    }

    public function viewHistory(): void
    {
        session()->flash('message', 'Chức năng xem lịch sử sẽ được triển khai sau.');
    }

    public function createSurvey(): void
    {
        $this->validate(Arr::only($this->rules(), ['surveyDate', 'selectedMarket']));

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
        $this->showSurveyForm = false;

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

        $this->validate(Arr::only($this->rules(), ['prices.*']));

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
    }

    public function logout()
    {
        Auth::guard('sale')->logout();

        return redirect('/khaosat/login');
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
        return view('livewire.khaosat.dashboard');
    }
}
