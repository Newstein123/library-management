<?php

use App\Http\Controllers\Api\v1\PermissionController;
use Illuminate\Support\Facades\Route;

Route::prefix('/')->middleware('auth:sanctum', 'role:admin|editor')->group(function () {
    Route::get('/', [PermissionController::class, 'index'])->middleware('permission:view permission');
    Route::post('give/{id}', [PermissionController::class, 'give_permission'])->middleware('permission:edit permission');
    Route::post('revoke/{id}', [PermissionController::class, 'revoke_permission'])->middleware('permission:edit permission');
});
