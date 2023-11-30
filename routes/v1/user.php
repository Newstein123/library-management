<?php

use App\Http\Controllers\Api\v1\MemberController;
use Illuminate\Support\Facades\Route;


Route::prefix('/')->middleware('auth:sanctum', 'role:admin')->group(function () {
    Route::get('/', [MemberController::class, 'index']);
    Route::get('/{id}', [MemberController::class, 'show']);
    Route::delete('delete/{id}', [MemberController::class, 'destroy']);
    Route::put('change-status/{id}', [MemberController::class, 'change_status']);
});
