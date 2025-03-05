<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\DTO;

use App\Core\Abstract\AbstractDTO;

class ProfileDTO extends AbstractDTO
{
    /**
     * @param int $id
     * @param string|null $username
     * @param string $email
     * @param string|null $password
     * @param string|null $newPassword
     */
    public function __construct(
        public readonly int $id,
        public readonly ?string $username,
        public readonly string $email,
        public readonly ?string $password,
        public readonly ?string $newPassword,
    )
    {
    }
}
