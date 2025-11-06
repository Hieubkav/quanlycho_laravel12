<?php

use Illuminate\Support\Facades\Route;

Route::get('/opcache-reset', function () {
    if (function_exists('opcache_reset')) {
        opcache_reset();
        return 'OPcache has been reset!';
    }
    
    return 'OPcache is not enabled or not available.';
})->name('opcache.reset');
