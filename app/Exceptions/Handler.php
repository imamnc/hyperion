<?php

namespace Itpi\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Handling error for :
     * - 404 Not Found
     *
     * @return void
     */
    public function render($request, Throwable $exception)
    {
        // Handling 404 Not Found
        if (
            strpos($request->getRequestUri(), '/api/', 0) === 0 && // Apply for prefix uri 'api' only
            get_class($exception) === NotFoundHttpException::class && // Check if exception is NotFoundHttpException
            $request->wantsJson() // Apply if request need json response
        ) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return parent::render($request, $exception);
    }
}
