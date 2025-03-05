<?php

declare(strict_types=1);

namespace Infrastructure\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GatewayTimeoutException extends Exception
{
    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return jsponse(trans('http-statuses.504'), Response::HTTP_GATEWAY_TIMEOUT);
    }
}
