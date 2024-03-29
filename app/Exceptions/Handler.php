<?php

namespace App\Exceptions;

use App\Helpers\ResponseFormatter;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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

    protected function unauthenticated($request, AuthenticationException $ex)
    {
        if ($request->expectsJson()) {
            return ResponseFormatter::error([
                'message' => 'Unauthenticated. Please Sign In.',
            ], 'Authentication Failed', 401);
        }
        
        return redirect()->route('login');
    }
}
