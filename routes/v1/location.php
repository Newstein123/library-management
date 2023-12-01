<?php

use App\Http\Controllers\Api\v1\LocationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LocationController::class, 'index']);
Route::get('{id}', [LocationController::class, 'show']);

Route::prefix('/')->middleware('auth:sanctum', 'role:admin')->group(function () {
    Route::post('create', [LocationController::class, 'store'])->middleware('permission:create location');
    Route::put('edit/{id}', [LocationController::class, 'update'])->middleware('permission:edit location');
    Route::delete('delete/{id}', [LocationController::class, 'delete'])->middleware('permission:delete location');
});
