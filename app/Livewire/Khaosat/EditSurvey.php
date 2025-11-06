<?php

namespace App\Livewire\Khaosat;

use App\Models\Product;
use App\Models\Survey;
use App\Models\SurveyItem;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\View\View;
use Livewire\Component;

class EditSurvey extends Component
{
    public Survey $survey;

    public $prices = [];

    protected function rules(): array
    {
        return [
            'prices.*' => 'nullable|numeric|min:0',
        ];
    }

    public function mount(Survey $survey): void
    {
        // Check ownership
        if (auth('sale')->id() !== $survey->sale_id) {
            abort(403);
        }

        $this->survey = $survey;
        $this->loadPrices();
    }

    public function loadPrices(): void
    {
        $products = Product::where('active', true)
            ->where('is_default', true)
            ->orderBy('order')
            ->get();

        $this->prices = [];

        foreach ($products as $product) {
            $existingItem = $this->survey->surveyItems()->where('product_id', $product->id)->first();
            $this->prices[$product->id] = $existingItem ? $existingItem->price : null;
        }
    }

    public function updatePrices(): void
    {
        $this->validate();

        $hasAtLeastOnePrice = collect($this->prices)
            ->contains(fn ($price) => is_numeric($price) && (float) $price > 0);

        if (! $hasAtLeastOnePrice) {
            $this->addError('prices', 'Vui lòng điền ít nhất một giá sản phẩm.');

            return;
        }

        foreach ($this->prices as $productId => $price) {
            if (! is_numeric($price) || (float) $price <= 0) {
                // Delete existing item if price is empty
                $this->survey->surveyItems()->where('product_id', $productId)->delete();

                continue;
            }

            SurveyItem::updateOrCreate(
                [
                    'survey_id' => $this->survey->id,
                    'product_id' => $productId,
                ],
                [
                    'price' => $price,
                    'notes' => '',
                ],
            );
        }

        session()->flash('message', 'Giá sản phẩm đã được cập nhật thành công!');
        $this->redirect(route('khaosat.history'));
    }

    public function back(): void
    {
        $this->redirect(route('khaosat.history'));
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
        return view('livewire.khaosat.edit-survey');
    }
}
