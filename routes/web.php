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
});
