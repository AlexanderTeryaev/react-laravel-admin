<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->wantsJson()) {   //add Accept: application/json in request
            return $this->handleApiException($request, $exception);
        }
        else if ($exception instanceof TokenExpiredException) {
            return response()->json(['token_expired'], $exception->getStatusCode());
        }
        else if ($exception instanceof TokenInvalidException) {
            return response()->json(['token_invalid'], $exception->getStatusCode());
        }
        else {
            $retval = parent::render($request, $exception);
        }
        return $retval;
    }

    private function handleApiException($request, Exception $exception)
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof HttpResponseException) {
            $exception = $exception->getResponse();
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }
        return $this->customApiResponse($exception);
    }

    private function customApiResponse($exception)
    {
        $statuscode = 500;
        $isCode = false;
        if (method_exists($exception, 'getStatusCode'))
            $statuscode = $exception->getStatusCode();
        else if (method_exists($exception, 'getCode')) {
            $isCode = true;
            $statuscode = $exception->getCode();
        }
        if ($statuscode == 0)
            $statuscode = 500;
        $response = [];
        switch ($statuscode) {
            case 401:
                $response['message'] = 'Unauthorized: ' . $exception->getMessage();
                break;
            case 403:
                $response['message'] = 'Forbidden: ' . $exception->getMessage();
                break;
            case 404:
                $response['message'] = 'Not Found: ' . $exception->getMessage();
                break;
            case 405:
                $response['message'] = 'Method Not Allowed: ' . $exception->getMessage();
                break;
            case 422:
                $response['message'] = $exception->original['message'];
                $response['errors'] = $exception->original['errors'];
                break;
            case 500:
                $response['message'] = 'Internal Server Error: ' . $exception->getMessage();
                break;
            default:
                $response['message'] = $exception->getMessage();
                break;
        }

        if (config('app.debug')) {
            if ($isCode)
                $response['trace'] = $exception->getTrace();
            $response['code'] = $statuscode;
        }

        return response()->json($response, $statuscode);
    }
}
