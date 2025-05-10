<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SaleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/v1/auth/logout', [AuthController::class, 'logout']);

    Route::prefix('v1')->group(function () {
        Route::post('/item/add', [ItemController::class, 'add']);
        Route::get('/item/all', [ItemController::class, 'getAll']);
        Route::get('/item/{name}', [ItemController::class, 'getByName']);
        Route::put('/item/update/{id}', [ItemController::class, 'update']);
        Route::delete('/item/delete/{id}', [ItemController::class, 'delete']);
    });

    Route::prefix('v1')->group(function () {
        Route::get('/sales', [SaleController::class, 'index']);
        Route::post('/sales', [SaleController::class, 'store']);
        Route::get('/sales/daily', [SaleController::class, 'dailyRevenue']);
        Route::get('/sales/monthly', [SaleController::class, 'monthlyRevenue']);
    });
});
