<?php

declare(strict_types=1);

namespace Infrastructure\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ServerErrorException extends Exception
{
    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return jsponse(['message' => trans('messages.server_error')], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
