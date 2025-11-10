<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\ClassificationController;
use App\Http\Controllers\Api\ArticleController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/signin', [AuthController::class, 'signin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/signout', [AuthController::class, 'signout']);
    
    Route::middleware('role:super_admin')->group(function () {
        Route::apiResource('users', UserController::class);
    });
    
    Route::middleware('role:super_admin,entry_manager')->group(function () {
        Route::apiResource('stock', StockController::class);
        Route::apiResource('articles', ArticleController::class);
    });
    
    Route::middleware('role:super_admin,classifications_manager')->group(function () {
        Route::apiResource('classifications', ClassificationController::class);
    });
});
