<?php

use Illuminate\Support\Facades\Route;

Route::get('/reviews', function () {
    abort_unless((bool) settings('catalog.reviews_enabled', true), 404);
    return redirect('/shop');
})->name('storefront.reviews');

// Storefront routes for the Reviews add-on.
