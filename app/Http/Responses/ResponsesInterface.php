<?php

namespace App\Http\Responses;


use Illuminate\Http\JsonResponse;

interface ResponsesInterface
{

    /**
     * Respond with a validation error.
     *
     * @param $errors
     *
     * @return JsonResponse
     */
    public function respondWithValidationError($errors): JsonResponse;

    /**
     * Respond with a not found error.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondNotFound($message = 'Not Found!'): JsonResponse;

    /**
     * Respond with an internal error.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondInternalError($message = 'Internal Server Error!'): JsonResponse;

    /**
     * Respond with an authorization error.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondAuthorizationError($message = 'You don\'t have the rights to access this resource.'): JsonResponse;

    /**
     * Respond with an authentication error.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondAuthenticationError($message = 'Forbidden!'): JsonResponse;

    /**
     * Respond with generic error.
     *
     * @param $message
     *
     * @return JsonResponse
     */
    public function respondWithError($message): JsonResponse;

    /**
     * Respond with data.
     *
     * @param $data
     *
     * @return JsonResponse
     */
    public function respond($data): JsonResponse;
}
