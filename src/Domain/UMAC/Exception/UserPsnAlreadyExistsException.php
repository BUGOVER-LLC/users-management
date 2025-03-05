<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Exception;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserPsnAlreadyExistsException extends Exception
{
    /**
     * @return JsonResponse
     */
    public function render(): \Illuminate\Http\JsonResponse
    {
        return jsponse(
            [
                'message' => trans('users.psn_exists', ['psn' => $this->message]),
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
