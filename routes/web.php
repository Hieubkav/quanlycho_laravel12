<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/test-settings', function () {
    $setting = \App\Models\Setting::where('key', 'global')->first();

    return response()->json($setting);
})->name('test.settings');

// Sale routes
Route::prefix('khaosat')->group(function () {
    Route::get('/login', function () {
        return view('khaosat-login');
    })->name('khaosat.login');

    Route::get('/', function () {
        return view('khaosat');
    })->middleware('auth.sale')->name('khaosat');

    Route::get('/create', function () {
        return view('khaosat-create');
    })->middleware('auth.sale')->name('khaosat.create');

    Route::get('/history', function () {
        return view('khaosat-history');
    })->middleware('auth.sale')->name('khaosat.history');

    Route::get('/{survey}/edit', function (App\Models\Survey $survey) {
        // Check if user owns this survey
        if (auth('sale')->id() !== $survey->sale_id) {
            abort(403);
        }

        return view('khaosat-edit', compact('survey'));
    })->middleware('auth.sale')->name('khaosat.edit');
});
