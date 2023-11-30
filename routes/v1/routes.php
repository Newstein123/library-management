<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::prefix('auth')->as('auth:')->group(
	base_path('routes/v1/auth.php'),
);

// Category Routes 
Route::prefix('category')->as('category:')->group(
	base_path('routes/v1/category.php'),
);

// Account Routes
Route::prefix('account')->as('account:')->group(
	base_path('routes/v1/account.php'),
);

// User Routes 

Route::prefix('user')->as('user:')->group(
	base_path('routes/v1/user.php'),
);

// Book Routes 

Route::prefix('book')->as('book:')->group(
	base_path('routes/v1/book.php'),
);