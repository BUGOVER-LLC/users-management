<?php

declare(strict_types=1);

namespace App\Domain\Micro\Exception;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserCannotHaveRoleException extends \Exception
{
    public function render(): JsonResponse
    {
        return jsponse(['message' => __('check-msg.user_cannot_have_role')], Response::HTTP_FORBIDDEN);
    }
}
