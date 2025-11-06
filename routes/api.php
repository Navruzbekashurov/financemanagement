<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DebtController;
use App\Http\Controllers\Api\GoalController;
use App\Http\Controllers\Api\TransactionController;
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

        Route::get('transactions', [TransactionController::class, 'index']);
        Route::post('transactions', [TransactionController::class, 'store']);
        Route::get('transactions/{transaction}', [TransactionController::class, 'show']);
        Route::put('transactions/{transaction}', [TransactionController::class, 'update']);
        Route::patch('transactions/{transaction}', [TransactionController::class, 'update']);
        Route::delete('transactions/{transaction}', [TransactionController::class, 'destroy']);

        Route::get('goals', [GoalController::class, 'index']);
        Route::post('goals', [GoalController::class, 'store']);
        Route::get('goals/{goal}', [GoalController::class, 'show']);
        Route::put('goals/{goal}', [GoalController::class, 'update']);
        Route::patch('goals/{goal}', [GoalController::class, 'update']);
        Route::delete('goals/{goal}', [GoalController::class, 'destroy']);

        Route::get('/categories', [CategoryController::class, 'index']);
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::get('/categories/{category}', [CategoryController::class, 'show']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::patch('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);


        Route::get('debts', [DebtController::class, 'index']);
        Route::post('debts', [DebtController::class, 'store']);
        Route::get('debts/{debt}', [DebtController::class, 'show']);
        Route::put('debts/{debt}', [DebtController::class, 'update']);
        Route::delete('debts/{debt}', [DebtController::class, 'destroy']);

    });
});
