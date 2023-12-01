<?php

use App\Http\Controllers\Api\v1\MemberController;
use Illuminate\Support\Facades\Route;


Route::prefix('/')->middleware('auth:sanctum', 'role:admin|editor')->group(function () {
    Route::get('/', [MemberController::class, 'index'])->middleware('permission:view members');
    Route::get('/{id}', [MemberController::class, 'show'])->middleware('permission:view members');
    Route::delete('delete/{id}', [MemberController::class, 'destroy'])->middleware('permission:delete members');
    Route::put('change-status/{id}', [MemberController::class, 'change_status'])->middleware('permission:edit members');
});
