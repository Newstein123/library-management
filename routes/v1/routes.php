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

// Location Routes 
Route::prefix('location')->as('location:')->group(
	base_path('routes/v1/location.php'),
);

// Language Routes 
Route::prefix('language')->as('language:')->group(
	base_path('routes/v1/language.php'),
);

// Author Routes 
Route::prefix('author')->as('author:')->group(
	base_path('routes/v1/author.php'),
);

// Publisher Routes 
Route::prefix('publisher')->as('publisher:')->group(
	base_path('routes/v1/publisher.php'),
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

// Book Request Routes 

Route::prefix('book-request')->as('bookrequest:')->group(
	base_path('routes/v1/bookrequest.php'),
);

// Transaction Routes 
Route::prefix('transaction')->as('transaction:')->group(
	base_path('routes/v1/transaction.php'),
);

// Genreal Setting Routes 
Route::prefix('general-setting')->as('general-setting:')->group(
	base_path('routes/v1/general-setting.php'),
);

// Permissions Routes 
Route::prefix('permission')->as('permission:')->group(
	base_path('routes/v1/permission.php'),
);