<?php

namespace App\Exceptions;

use App\Constants\AppConstants;
use App\Http\Responses\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as StatusCode;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
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
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param  Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        Log::error("Exception Handler " . $e->__toString());

        if ($request->wantsJson()) {
            $response = Response::create();
            if ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
                $response
                    ->setMessage(__(AppConstants::RESPONSE_CODES_MESSAGES[AppConstants::APP_1002]))
                    ->setStatusCode(StatusCode::HTTP_NOT_FOUND)
                    ->setResponseCode(AppConstants::APP_1002);
            } elseif ($e instanceof AuthorizationException) {
                $response
                    ->setMessage(__(AppConstants::RESPONSE_CODES_MESSAGES[AppConstants::APP_1001]))
                    ->setStatusCode(StatusCode::HTTP_FORBIDDEN)
                    ->setResponseCode(AppConstants::APP_1001);
            } elseif ($e instanceof AuthenticationException) {
                $response
                    ->setMessage(__(AppConstants::RESPONSE_CODES_MESSAGES[AppConstants::APP_1007]))
                    ->setStatusCode(StatusCode::HTTP_UNAUTHORIZED)
                    ->setResponseCode(AppConstants::APP_1007);
            } elseif ($e instanceof ValidationException) {
                $response
                    ->setMessage(__(AppConstants::RESPONSE_CODES_MESSAGES[AppConstants::APP_1004]))
                    ->setStatusCode(StatusCode::HTTP_UNPROCESSABLE_ENTITY)
                    ->setErrors($e->errors())
                    ->setResponseCode(AppConstants::APP_1004);
            } elseif ($e instanceof TokenMismatchException) {
                $response
                    ->setMessage(__(AppConstants::RESPONSE_CODES_MESSAGES[AppConstants::APP_1003]))
                    ->setStatusCode(419)
                    ->setResponseCode(AppConstants::APP_1003);
            } elseif ($e instanceof MethodNotAllowedHttpException) {

                $response
                    ->setMessage(__(AppConstants::RESPONSE_CODES_MESSAGES[AppConstants::APP_1006]))
                    ->setStatusCode(StatusCode::HTTP_METHOD_NOT_ALLOWED)
                    ->setResponseCode(AppConstants::APP_1006);
            } else {
                $response
                    ->setMessage(__(AppConstants::RESPONSE_CODES_MESSAGES[AppConstants::APP_1005]))
                    ->setStatusCode(StatusCode::HTTP_INTERNAL_SERVER_ERROR)
                    ->setResponseCode(AppConstants::APP_1005);
            }
            return $response->failure();
        } else {
            if ($e instanceof RouteNotFoundException) {

                return redirect()->route('login');
            }
        }
        return parent::render($request, $e);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return parent::unauthenticated($request, $exception);
    }
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
