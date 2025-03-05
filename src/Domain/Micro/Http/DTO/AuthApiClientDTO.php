<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\DTO;

use App\Core\Abstract\AbstractDTO;

class AuthApiClientDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $secret,
        public readonly string $domain,
    )
    {
    }
}
