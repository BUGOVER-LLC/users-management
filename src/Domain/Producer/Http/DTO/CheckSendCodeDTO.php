<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\DTO;

use App\Core\Abstract\AbstractDTO;

class CheckSendCodeDTO extends AbstractDTO
{
    public function __construct(
        public readonly int|string $code,
        public readonly string $email,
        public readonly string $authenticator,
    )
    {
    }
}
