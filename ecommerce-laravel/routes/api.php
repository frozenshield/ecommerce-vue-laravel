<?php

use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\CouponController;
use Illuminate\Support\Facades\Route;


//Route::apiResource('products', ProductsController::class);
// Alternatively, you can define the routes manually as follows:

Route::prefix('products')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [ProductsController::class, 'index']);
        Route::get('/{id}', [ProductsController::class, 'show']);
        Route::post('/', [ProductsController::class, 'addProduct']);
        Route::put('/{id}', [ProductsController::class, 'update']);
        Route::delete('/{id}', [ProductsController::class, 'destroy']);
    });
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

Route::prefix('user_address')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [UserAddressController::class, 'getAllAddress']);
        Route::post('/', [UserAddressController::class, 'addNewAddress']);
        Route::put('/{user_address_id}', [UserAddressController::class, 'editAddress']);
        Route::delete('/{user_address_id}', [UserAddressController::class, 'deleteAddress']);
    });
});

Route::prefix('profile')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [ProfilesController::class, 'getMyProfile']);
        Route::post('/', [ProfilesController::class, 'addProfile']);
        Route::put('/{profile_id}', [ProfilesController::class, 'editProfile']);
    });
});

Route::prefix('product_category')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/all', [ProductCategoryController::class, 'getAllCategory']);
        Route::get('/', [ProductCategoryController::class, 'getPaginatedCategory']);
        Route::post('/', [ProductCategoryController::class, 'addProductCategory']);
        Route::get('/{product_category_id}', [ProductCategoryController::class, 'getSpecificCategory']);
        Route::put('/{product_category_id}', [ProductCategoryController::class, 'editCategory']);
        Route::patch('/{product_category_id}/toggle-status', [ProductCategoryController::class, 'toggleCategoryStatus']);
        Route::delete('/{product_category_id}', [ProductCategoryController::class, 'deleteCategory']);
    });
});

Route::prefix('wishlist')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [WishlistController::class, 'getAllWishlist']);
        Route::post('/', [WishlistController::class, 'addWishlist']);
        Route::delete('/{wishlistId}', [WishlistController::class, 'removeWishlist']);
    });
});

Route::prefix('coupon')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [CouponController::class, 'getAllCoupon']);
        Route::get('/{coupon_id}', [CouponController::class, 'getSpecificCoupon']);
        Route::put('/{coupon_id}', [CouponController::class, 'editCoupon']);
        Route::patch('/{coupon_id}/toggle-status', [CouponController::class, 'toggleCoupon']);
        Route::post('/', [CouponController::class, 'createCoupon']);
        Route::delete('/{coupon_id}', [CouponController::class, 'deleteCoupon']);
    });
});