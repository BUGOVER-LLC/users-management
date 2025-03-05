<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\DTO;

use App\Core\Abstract\AbstractDTO;

class SendEmailDTO extends AbstractDTO
{
    /**
     * @param string $email
     * @param string $authenticator
     */
    public function __construct(
        public readonly string $email,
        public readonly string $authenticator,
    )
    {
    }
}
