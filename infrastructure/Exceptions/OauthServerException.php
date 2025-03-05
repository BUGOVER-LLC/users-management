<?php

declare(strict_types=1);

namespace Infrastructure\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OauthServerException extends Exception
{
    /**
     * Render an exception into an Http response.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function render(Request $request): JsonResponse
    {
        return jsponse(['message' => trans('http-statuses.501')], Response::HTTP_NOT_IMPLEMENTED);
    }
}
