<?php

use App\Http\Controllers\Api\AddToCartController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\PaketController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
 
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
 
// Group Auth Anctum
Route::group(['middleware' => ['auth:sanctum']], function () {
 
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});
 
//  Group Auth Sanctum Admin
Route::group(['middleware' => ['auth:sanctum', AdminMiddleware::class], 'prefix' => 'admin'], function () {
    // paket
    Route::post('/paket', [PaketController::class, 'store']);
    Route::get('/paket/{id}', [PaketController::class, 'show']);
    Route::post('/paket/{id}', [PaketController::class, 'update']);
    Route::delete('/paket/{id}', [PaketController::class, 'destroy']);
    Route::get('/paket', [PaketController::class, 'index']);
 
    // products
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::post('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    Route::get('/products', [ProductController::class, 'index']);
});

Route::group(['middleware'=> ['auth:sanctum', 'isCustomer']], function () {
    Route::get('/add_to_cart', [AddToCartController::class,'index']);
    Route::post('/add_to_cart', [AddToCartController::class,'store']);
    Route::patch('/add_to_cart/{id}', [AddToCartController::class,'update']);
    Route::delete('/add_to_cart/{id}', [AddToCartController::class,'destroy']);

    Route::post('/checkout', [CheckoutController::class,'store']);

});
 
// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);