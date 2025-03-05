<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class InvalidUserProfileDataException extends Exception
{
    public function render()
    {
        return jsponse(
            ['message' => trans('users.psn_undefined', ['psn' => $this->message])],
            Response::HTTP_NOT_FOUND
        );
    }
}
