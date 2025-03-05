<?php

declare(strict_types=1);

namespace Infrastructure\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpException extends Exception
{
    /**
     * Render an exception into an Http response.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function render(Request $request): JsonResponse
    {
        $code = 0 === $this->getCode() ? Response::HTTP_INTERNAL_SERVER_ERROR : $this->getCode();

        $response = [
            'message' => $this->getMessage(),
            'errors' => [],
        ];

        return jsponse($response, $code);
    }
}
