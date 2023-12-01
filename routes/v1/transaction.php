<?php

use App\Http\Controllers\Api\v1\TransactionController;
use Illuminate\Support\Facades\Route;


Route::prefix('/')->middleware('auth:sanctum', 'role:member|admin')->group(function () {
    Route::get('{id}', [TransactionController::class, 'show']);
});

Route::prefix('/')->middleware('auth:sanctum', 'role:member')->group(function () {
    Route::get('user/{id}', [TransactionController::class, 'get_user_transactions']);
});

Route::prefix('/')->middleware('auth:sanctum', 'role:admin')->group(function () {
    Route::put('user/return-book', [TransactionController::class, 'return_book']);
    Route::get('/', [TransactionController::class, 'index']);
});