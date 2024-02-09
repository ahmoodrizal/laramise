<?php

use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\MidtransCallbackController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);

// Midtrans Callback
Route::post('/callback', [MidtransCallbackController::class, 'callback']);

// Authenticated API
Route::middleware(['auth:sanctum'])->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/update-fcm', [AuthController::class, 'updateFcmId']);

    // Address
    Route::apiResource('addresses', AddressController::class);

    // Order
    Route::post('/order', [OrderController::class, 'order']);
    Route::get('/order/status/{order}', [OrderController::class, 'checkStatus']);
    Route::get('/orders', [OrderController::class, 'ordersByUser']);
    Route::get('/order/{id}', [OrderController::class, 'orderById']);
});
