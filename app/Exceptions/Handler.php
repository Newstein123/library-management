<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {   
        // unauthorized Role  custom message
        if ($exception instanceof UnauthorizedException) {
            return response()->json([
                'success' => false,
                'error'   => [
                    'code' => 'E0005',
                    'message' => 'Access denied. User does not have the required role.',
                ]
            ], 403);
        }

        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'success' => false,
                'error'   => [
                    'code' => 'E0005',
                    'message' => 'Access denied. User does not have the required permission.',
                ]
            ], 403);
        }

        // unauthorized user custom message 
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'error'   => [
                    'code' => 'E0001',
                    'message' => 'Authentication required. Please log in.',
                ]
            ], 401);
        }

        return parent::render($request, $exception);
    }
}
