<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ReviewsController;
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


Route::prefix('cart')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [CartController::class, 'showAllCart']);
        Route::get('/{cart_id}', [CartController::class, 'showSpecificCart']);
        Route::post('/', [CartController::class, 'addToCart']);
        Route::put('/{cart_id}', [CartController::class, 'editQuantity']);
        Route::delete('/{cart_id}', [CartController::class, 'removeToCart']);
    });
});


Route::prefix('reviews')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [ReviewsController::class, 'getAllReviews']);
        Route::post('/', [ReviewsController::class, 'createReview']);
        Route::get('/{review_id}', [ReviewsController::class, 'getSpecificReview']);
        Route::put('/{review_id}', [ReviewsController::class, 'editReview']);
        Route::delete('/{review_id}', [ReviewsController::class, 'deleteReview']);
    });
});