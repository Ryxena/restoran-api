<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

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

Route::prefix('/auth')->controller(UserController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->controller(OrderController::class)->group(function () {
    Route::post('/add-cart/{id}', 'store');
    Route::post('/checkout', 'checkout');
    Route::get('/most-freq-product', [ProductController::class, 'show']);
});

// For Admin
Route::prefix('/admin')->middleware(['auth:sanctum','auth.admin'])->group(function(){
    Route::get('/transaction-report-days/{date}', [OrderController::class, 'transaction_report_by_day']);
    Route::get('/transaction-report-months/{month}', [OrderController::class, 'transaction_report_by_month']);
});
