<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
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

        $this->renderable(function (Throwable $exception) {

            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            if ($exception instanceof HttpResponseException) {
                $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            } elseif ($exception instanceof MethodNotAllowedHttpException) {
                $status = Response::HTTP_METHOD_NOT_ALLOWED;
                $exception = new MethodNotAllowedHttpException([], 'HTTP_METHOD_NOT_ALLOWED', $exception);
            } elseif ($exception instanceof NotFoundHttpException || $exception instanceof NotFoundException) {
                $status = Response::HTTP_NOT_FOUND;
                $exception = new NotFoundHttpException($exception->getMessage() ?? "Not found", $exception);
            } elseif ($exception instanceof AuthorizationException || $exception instanceof ForbiddenException) {
                $status = Response::HTTP_FORBIDDEN;
                $exception = new AuthorizationException($exception->getMessage() ?? 'Forbidden', $status);
            } elseif ($exception instanceof \Dotenv\Exception\ValidationException && $exception->getResponse()) {
                $status = Response::HTTP_BAD_REQUEST;
                $exception = new \Dotenv\Exception\ValidationException('HTTP_BAD_REQUEST', $status, $exception);
            } elseif ($exception) {
                $exception = new HttpException($status, 'HTTP_INTERNAL_SERVER_ERROR');
            }

            return response()->json([
                'status' => $status,
                'message' => $exception->getMessage()
            ], $status);
        });
    }
}
