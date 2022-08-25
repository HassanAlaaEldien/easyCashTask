<?php

namespace App\Exceptions;

use App\Http\Responses\ResponsesInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response as RequestResponse;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiHandler extends ExceptionHandler
{
    /**
     * @var ResponsesInterface
     */
    private $apiResponder;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * @param ResponsesInterface $apiResponder
     */
    public function __construct(ResponsesInterface $apiResponder)
    {
        $this->apiResponder = $apiResponder;
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return RequestResponse|JsonResponse
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (($exception instanceof NotFoundHttpException || $exception instanceof ModelNotFoundException)) {
            return $this->apiResponder->respondNotFound();
        } elseif ($exception instanceof AuthorizationException || $exception instanceof AccessDeniedHttpException) {
            return $this->apiResponder->respondAuthorizationError();
        }

        if ($exception instanceof HttpResponseException) {
            return $exception->getResponse();
        } elseif ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        } elseif ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $request->expectsJson() || $request->isJson() || $request->is('api/*')
            ? $this->prepareJsonResponse($request, $exception)
            : $this->prepareResponse($request, $exception);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param \Illuminate\Validation\ValidationException $e
     * @param Request $request
     * @return JsonResponse
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->response ?? $e->validator->errors()->getMessages();
        $errors = call_user_func_array('array_merge', array_values($errors));

        return $this->apiResponder->respondWithValidationError($errors[0]);
    }


    /**
     * Converts an authenticated exception into an unauthenticated response.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return JsonResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->apiResponder->respondAuthenticationError();
    }
}
