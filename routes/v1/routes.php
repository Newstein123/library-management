<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->as('auth:')->group(
	base_path('routes/v1/auth.php'),
);

Route::prefix('category')->as('category:')->group(
	base_path('routes/v1/category.php'),
);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return response()->json([
            'data' => "hello world"
        ]);
});