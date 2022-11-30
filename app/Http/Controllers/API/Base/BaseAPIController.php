<?php

namespace App\Http\Controllers\API\Base;

use App\Http\Controllers\Controller;

class BaseAPIController extends Controller
{
    private int $statusCode;

    private const HTTP_OK = 200;
    private const HTTP_CREATED = 201;
    private const HTTP_BAD_REQUEST = 400;
    private const HTTP_FORBIDDEN = 403;
    private const HTTP_NOT_FOUND = 404;
    private const HTTP_METHOD_NOT_ALLOWED = 405;
    private const HTTP_INTERNAL_SERVER_ERROR = 500;

    public function respondSuccess(string $message, array $data)
    {
        return $this->setStatusCode(self::HTTP_OK)->respond($message, true, $data);
    }

    public function respondInvalidValiation(string $message)
    {
        return $this->setStatusCode(self::HTTP_METHOD_NOT_ALLOWED)->respond($message, false);
    }

    public function respondClientError(string $message)
    {
        return $this->setStatusCode(self::HTTP_BAD_REQUEST)->respond($message, false);
    }

    public function respondForbidden(string $message)
    {
        return $this->setStatusCode(self::HTTP_FORBIDDEN)->respond($message, false);
    }

    public function respondInternalError(string $message)
    {
        return $this->setStatusCode(self::HTTP_INTERNAL_SERVER_ERROR)->respond($message, true);
    }

    public function respondNotFound(string $message)
    {
        return $this->setStatusCode(self::HTTP_NOT_FOUND)->respond($message);
    }

    public function respondCreated(string $message, array $data)
    {
        return $this->setStatusCode(self::HTTP_CREATED)->respond($message, true, $data);
    }

    private function respond(string $message = '', bool $isSuccess = false, array $data = [])
    {
        $responseData = [
            'success' => $isSuccess,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($responseData)->setStatusCode($this->getStatusCode());
    }

    private function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    private function getStatusCode(): int
    {
        return $this->statusCode;
    }

}
