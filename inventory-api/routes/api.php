<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

// Produk
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);
Route::get('/products/search', [ProductController::class, 'search']);
Route::post('/products/update-stock', [ProductController::class, 'updateStock']);
Route::get('/inventory/value', [ProductController::class, 'inventoryValue']);

// Kategori
Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories', [CategoryController::class, 'index']);

