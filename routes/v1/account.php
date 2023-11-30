<?php

use App\Http\Controllers\Api\v1\AdminAccountController;
use App\Http\Controllers\Api\v1\MemberAccountController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin/')->middleware('auth:sanctum', 'role:admin')->group(function () {
    Route::get('/{id}', [AdminAccountController::class, 'show']);
    Route::put('edit/{id}', [AdminAccountController::class, 'update']);
    Route::put('change-password/{id}', [AdminAccountController::class, 'change_password']);
});

Route::prefix('member/')->middleware('auth:sanctum', 'role:member')->group(function () {
    Route::get('/{id}', [MemberAccountController::class, 'show']);
    Route::put('edit/{id}', [MemberAccountController::class, 'update']);
    Route::delete('delete/{id}', [MemberAccountController::class, 'delete']);
});