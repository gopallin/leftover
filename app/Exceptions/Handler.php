<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
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
        $this->renderable(function (Throwable $e, $request) {
            if ($e instanceof ValidationException) {
                $statusCode = 422;
                $message = $e->validator->errors();
            } elseif ($e instanceof AuthenticationException) {
                $statusCode = 401;
                $message = $e->getMessage();
            } else {
                $statusCode = 400;
                $message = $e->getMessage();
            }

            return response()->json([
                'ok' => false,
                'messages' => $message
            ], $statusCode);
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
