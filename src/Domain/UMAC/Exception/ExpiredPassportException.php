<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Exception;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ExpiredPassportException extends ValidationException
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function render(Request $request): JsonResponse
    {
        return jsponse(
            [
                'status' => Response::HTTP_OK,
                'message' => __('http-statuses.200'),
                'errors' => $this->validator->errors()->getMessages(),
            ],
            Response::HTTP_OK,
        );
    }
}
