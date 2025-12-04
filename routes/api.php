<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/stores', [StoreController::class, 'index']);
    Route::post('/store-add', [StoreController::class, 'store']);
    Route::get('/stocks', [StockController::class, 'index']);          // For Tabulator server mode
    Route::post('/stocks/create', [StockController::class, 'create']); // Get data for creating stock
    Route::post('/stocks', [StockController::class, 'store']);         // Create single or bulk create
    Route::delete('/stocks/{id}', [StockController::class, 'destroy']);
});
