<?php

declare(strict_types=1);

namespace App\Domain\UMAA\Exception;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InvitationDoesntExists extends Exception
{
    public function render(): JsonResponse
    {
        return jsponse(['message' => __('check-msg.invitation_not_found')], Response::HTTP_NOT_FOUND);
    }
}
