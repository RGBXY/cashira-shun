<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionCalculatorController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'index');


Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products/searchProductName', [ProductController::class, 'searchProductName']);
Route::get('/products/searchProductBarcode', [ProductController::class, 'searchProductBarcode']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::put('/products/{product}/update', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'delete']);

Route::post('/store-calculator', [TransactionCalculatorController::class, 'store']);
Route::post('/test-print', [TransactionCalculatorController::class, 'test']);

Route::post('/searchByBarcode', [TransactionController::class, 'searchByBarcode']);
Route::post('/searchByName', [TransactionController::class, 'searchProductName']);
Route::post('/print-receipt', [TransactionController::class, 'test']);

