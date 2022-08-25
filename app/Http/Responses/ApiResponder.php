<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponder implements ResponsesInterface
{
    /**
     * @var int
     */
    protected int $statusCode = 200;

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Set the status code according to a passed int
     *
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode): static
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Respond with a validation error.
     *
     * @param $errors
     *
     * @return JsonResponse
     */
    public function respondWithValidationError($errors): JsonResponse
    {
        return $this->setStatusCode(422)->respondWithError($errors);
    }

    /**
     * Respond with a not found error.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondNotFound($message = 'Not Found!'): JsonResponse
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }

    /**
     * Respond with an internal error.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondInternalError($message = 'Internal Server Error!'): JsonResponse
    {
        return $this->setStatusCode(500)->respondWithError($message);
    }

    /**
     * Respond with an authorization error.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondAuthorizationError($message = 'You don\'t have the rights to access this resource.'): JsonResponse
    {
        return $this->setStatusCode(403)->respondWithError($message);
    }

    /**
     * Respond with an authentication error.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondAuthenticationError($message = 'Forbidden!'): JsonResponse
    {
        return $this->setStatusCode(401)->respondWithError($message);
    }

    /**
     * Respond with generic error.
     *
     * @param $message
     *
     * @return JsonResponse
     */
    public function respondWithError($message): JsonResponse
    {
        return $this->respond(['message' => $message]);
    }

    /**
     * Respond with data.
     *
     * @param $data
     *
     * @return JsonResponse
     */
    public function respond($data, $headers = []): JsonResponse
    {
        $data['status_code'] = $this->getStatusCode();
        return response()->json($data, $this->getStatusCode(), $headers);
    }
}
