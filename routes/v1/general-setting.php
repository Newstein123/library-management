<?php

use App\Http\Controllers\Api\v1\GeneralSettingController;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Route;

Route::prefix('/')->middleware('auth:sanctum', 'role:admin')->group(function () {
    Route::get('/', [GeneralSettingController::class, 'index']);
    Route::post('create', [GeneralSettingController::class, 'store']);
    Route::put('edit/{id}', [GeneralSettingController::class, 'update']);
});