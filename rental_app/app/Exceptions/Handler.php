<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

final class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->renderable(function (\Exception $e, $request) {
            $response = match (true) {
                $e instanceof ValidationException => [
                    [
                        'message' => 'Validation Error',
                        'errors' => $e->errors(),
                    ],
                    400,
                ],
                default => [
                    [
                        'message' => get_class($e),
                        'errors' => $e->getMessage(),
                        'path' => url()->current(),
                        'prewpath' => url()->previous(),
                        'method' => request()->method(),
                    ],
                    404,
                ],
            };

            return response()->json(...$response);
        });
    }
}
