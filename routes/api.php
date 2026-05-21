<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);


// ADMIN seulement
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});



Route::middleware(['auth:sanctum'])->get('/admin/stats', function () {
    return [
        'products' => \App\Models\Product::count(),
        'orders' => \App\Models\Order::count(),
        'revenue' => \App\Models\Order::sum('total')
    ];
});



Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
});


Route::post('/orders', [OrderController::class, 'store']);
Route::post('/payment', [PaymentController::class, 'pay']);
Route::post('/payment/webhook', [PaymentController::class, 'webhook']);