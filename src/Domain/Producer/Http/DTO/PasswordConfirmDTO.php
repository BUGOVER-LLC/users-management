<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\DTO;

use App\Core\Abstract\AbstractDTO;

class PasswordConfirmDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $repeatPassword,
    ) {
    }
}
