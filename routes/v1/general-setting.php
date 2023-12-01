<?php

use App\Http\Controllers\Api\v1\GeneralSettingController;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Route;

Route::prefix('/')->middleware('auth:sanctum', 'role:admin|editor')->group(function () {
    Route::get('/', [GeneralSettingController::class, 'index'])->middleware('permission:view gs');
    Route::post('create', [GeneralSettingController::class, 'store'])->middleware('permission:create gs');
    Route::put('edit/{id}', [GeneralSettingController::class, 'update'])->middleware('permission:edit gs');
});