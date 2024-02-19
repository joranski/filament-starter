<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;


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
            if ($this->shouldReport($e)) {
                \BezhanSalleh\FilamentExceptions\FilamentExceptions::report($e);
            }
        });

        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Object not found'], 404);
            }
        });
    }

    function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {
            if ($exception->getStatusCode() == 404) {
                return response()->view('errors.404', [], 404);
            }
            if ($exception->getStatusCode() == 500) {
                return response()->view('errors.500', [], 500);
            }
        }
        return parent::render($request, $exception);
     }
}
