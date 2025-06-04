<?php

use App\Http\Controllers\OrdersController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;


//Route::apiResource('products', ProductsController::class);
// Alternatively, you can define the routes manually as follows:

Route::prefix('products')->group(function () {
    Route::get('/', [ProductsController::class, 'index']);
    Route::get('/{id}', [ProductsController::class, 'show']);
    Route::post('/', [ProductsController::class, 'store']);
    Route::put('/{id}', [ProductsController::class, 'update']);
    Route::delete('/{id}', [ProductsController::class, 'destroy']);
});


Route::prefix('users')->group(function () {
    Route::post('/register', [UserController::class, 'store']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [UserController::class, 'logout']);
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });
});

Route::post('/login', [UserController::class, 'login']);


Route::prefix('orders')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [OrdersController::class, 'store']);
        Route::get('/', [OrdersController::class, 'index']);
        Route::get('/{id}', [OrdersController::class, 'show']);
        Route::put('/{id}', [OrdersController::class, 'update']);
        Route::delete('/{id}', [OrdersController::class, 'destroy']);
    });
});
