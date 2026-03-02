<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use  App\Http\Controllers\OrderController;
//Auth
Route::post("register", [AuthController::class, "register"]);
Route::post("login", [AuthController::class, "login"]);
Route::post("logout", [AuthController::class, "logout"])->middleware("auth:sanctum");
//User
Route::prefix("users")->middleware("auth:sanctum", "role:admin")->group(function () {
    Route::apiResource('', UserController::class);
    Route::post('{user}/products', [UserController::class, 'attach']);
});

//Product
Route::prefix("products")->middleware("auth:sanctum", "role:admin")->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::post('products/{product}/users', [ProductController::class, 'attach']);
});
//Cart
Route::prefix("cart")->middleware("auth:sanctum", "role:customer")->group(function () {
    Route::get('', [CartController::class, 'show']);
    Route::post('add/{product}', [CartController::class, 'add']);
    Route::put('update/{product}', [CartController::class, 'update']);
    Route::delete('clear', [CartController::class, 'destroy']);
    //order
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel']);
    });
});
