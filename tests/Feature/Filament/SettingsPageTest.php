<?php

use App\Filament\Pages\Settings;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('settings page can be rendered', function () {
    $user = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->assertOk();
});

it('settings page shows data', function () {
    $user = User::factory()->create(['role' => 'admin']);
    Setting::create([
        'key' => 'global',
        'brand_name' => 'Test Brand',
        'active' => true,
        'order' => 1,
    ]);

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->assertFormSet([
            'brand_name' => 'Test Brand',
            'active' => true,
        ]);
});
