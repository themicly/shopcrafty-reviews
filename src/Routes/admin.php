<?php

use Illuminate\Support\Facades\Route;

Route::prefix('catalog')->name('catalog.')->middleware('can:manage-products')->group(function () {
    Route::view('/reviews', 'reviews::admin.reviews')->name('reviews.index');
});

// Authenticated admin routes for the Reviews add-on.
