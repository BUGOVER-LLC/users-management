<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Exception;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserPsnDoesntExistsException extends Exception
{
    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return jsponse(
            [
                'status' => Response::HTTP_NOT_FOUND,
                'message' => trans('users.psn_undefined', ['psn' => $this->message]),
            ],
            Response::HTTP_NOT_FOUND,
        );
    }
}
