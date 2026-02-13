<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MaskController;
use App\Models\User;
use PHPUnit\Metadata\Group;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use Illuminate\Container\Attributes\Auth;
use App\Http\Controllers\AuthController;
//user
Route::post("register",[AuthController::class,"register"]);
Route::post("login",[AuthController::class,"login"]);
Route::post("logout",[AuthController::class,"logout"])->middleware("auth:sanctum");
//customers routes
Route::prefix("users")->middleware("auth:sanctum","role:admin")->group(function(){
Route::apiResource('', UserController::class);
Route::post('{user}/products',[UserController::class,'attach']);

});

//products routes
Route::prefix("products")->middleware("auth:sanctum","role:admin")->group(function(){
Route::apiResource('products', ProductController::class);
Route::post('products/{product}/users',[ProductController::class,'attach']);
});
//Cart
Route::prefix("cart")->middleware("auth:sanctum","role:customer")->group(function(){
Route::get('', [CartController::class, 'show']);
Route::post('add/{product}', [CartController::class, 'add']);
Route::put('update/{product}', [CartController::class, 'update']);
Route::delete('clear', [CartController::class, 'destroy']);
});





?>