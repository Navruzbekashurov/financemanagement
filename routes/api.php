<?php

use App\Http\Controllers\Api\TransactionApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::get('google/url', [AuthController::class, 'getGoogleAuthUrl']);
    Route::get('google/callback', [AuthController::class, 'handleGoogleCallback']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);

        Route::get('transactions', [TransactionApiController::class, 'index'])->name('transactions.index');
        Route::post('transactions', [TransactionApiController::class, 'store'])->name('transactions.store');
        Route::get('transactions/{transaction}', [TransactionApiController::class, 'show'])->name('transactions.show');
        Route::put('transactions/{transaction}', [TransactionApiController::class, 'update'])->name('transactions.update');
        Route::patch('transactions/{transaction}', [TransactionApiController::class, 'update']);
        Route::delete('transactions/{transaction}', [TransactionApiController::class, 'destroy'])->name('transactions.destroy');
    });
});
