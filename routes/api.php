<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:60,1', ForceJsonResponse::class])->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product:slug}', [ProductController::class, 'show']);
    Route::get('/categories', [ProductController::class, 'categories']);
});
