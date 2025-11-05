<?php

use App\Livewire\Khaosat\Dashboard;
use App\Models\Market;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Survey;
use App\Models\SurveyItem;
use App\Models\Unit;
use Illuminate\Support\Carbon;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('allows sale to create a survey for an assigned market', function () {
    Carbon::setTestNow('2025-05-11 08:00:00');

    try {
        $sale = Sale::factory()->create(['active' => true]);
        $market = Market::factory()->create(['active' => true]);

        $sale->markets()->attach($market);

        actingAs($sale, 'sale');

        Livewire::test(Dashboard::class)
            ->call('startSurvey')
            ->set('selectedMarket', (string) $market->id)
            ->call('createSurvey')
            ->assertHasNoErrors()
            ->assertSet('currentSurvey.sale_id', $sale->id);

        expect(Survey::count())->toBe(1);

        $survey = Survey::first();

        expect($survey)->not->toBeNull();
        expect($survey->sale_id)->toBe($sale->id);
        expect($survey->market_id)->toBe($market->id);
        expect($survey->survey_day->toDateString())->toBe(Carbon::now()->toDateString());
    } finally {
        Carbon::setTestNow();
    }
});

it('prevents sale from creating a survey in an unassigned market', function () {
    Carbon::setTestNow('2025-05-11 09:00:00');

    try {
        $sale = Sale::factory()->create(['active' => true]);
        $assignedMarket = Market::factory()->create(['active' => true]);
        $otherMarket = Market::factory()->create(['active' => true]);

        $sale->markets()->attach($assignedMarket);

        actingAs($sale, 'sale');

        Livewire::test(Dashboard::class)
            ->call('startSurvey')
            ->set('selectedMarket', (string) $otherMarket->id)
            ->call('createSurvey')
            ->assertHasErrors(['selectedMarket']);

        expect(
            Survey::query()
                ->where('sale_id', $sale->id)
                ->where('market_id', $otherMarket->id)
                ->exists()
        )->toBeFalse();
    } finally {
        Carbon::setTestNow();
    }
});

it('stores survey prices when at least one value is provided', function () {
    Carbon::setTestNow('2025-05-11 10:00:00');

    try {
        $sale = Sale::factory()->create(['active' => true]);
        $market = Market::factory()->create(['active' => true]);
        $unit = Unit::create([
            'name' => 'Kg',
            'active' => true,
            'order' => 1,
        ]);

        $productWithPrice = Product::create([
            'name' => 'Gao',
            'unit_id' => $unit->id,
            'is_default' => true,
            'notes' => null,
            'active' => true,
            'order' => 1,
        ]);

        $productWithoutPrice = Product::create([
            'name' => 'Duong',
            'unit_id' => $unit->id,
            'is_default' => true,
            'notes' => null,
            'active' => true,
            'order' => 2,
        ]);

        $sale->markets()->attach($market);

        actingAs($sale, 'sale');

        $component = Livewire::test(Dashboard::class)
            ->call('startSurvey')
            ->set('selectedMarket', (string) $market->id)
            ->call('createSurvey');

        $component
            ->set("prices.{$productWithPrice->id}", '15000')
            ->set("prices.{$productWithoutPrice->id}", null)
            ->call('savePrices')
            ->assertHasNoErrors()
            ->assertSet('currentSurvey', null);

        expect(Survey::count())->toBe(1);
        expect(SurveyItem::count())->toBe(1);
        expect(SurveyItem::first()->price)->toBe('15000.00');
    } finally {
        Carbon::setTestNow();
    }
});

it('requires at least one price before saving a survey', function () {
    Carbon::setTestNow('2025-05-11 11:00:00');

    try {
        $sale = Sale::factory()->create(['active' => true]);
        $market = Market::factory()->create(['active' => true]);
        $unit = Unit::create([
            'name' => 'Kg',
            'active' => true,
            'order' => 1,
        ]);

        $product = Product::create([
            'name' => 'Ca',
            'unit_id' => $unit->id,
            'is_default' => true,
            'notes' => null,
            'active' => true,
            'order' => 1,
        ]);

        $sale->markets()->attach($market);

        actingAs($sale, 'sale');

        $component = Livewire::test(Dashboard::class)
            ->call('startSurvey')
            ->set('selectedMarket', (string) $market->id)
            ->call('createSurvey');

        $component
            ->set("prices.{$product->id}", '')
            ->call('savePrices')
            ->assertHasErrors(['prices']);

        expect(SurveyItem::count())->toBe(0);
    } finally {
        Carbon::setTestNow();
    }
});
