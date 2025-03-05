<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Http\DTO;

use App\Core\Abstract\AbstractDTO;
use Carbon\Carbon;

class CreateUserDTO extends AbstractDTO
{
    public function __construct(
        public readonly int $psn,
        public readonly string $email,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly ?string $patronymic,
        public readonly string|Carbon $dateBirth,
        public readonly ?int $roleId,
        public readonly ?int $attributeId,
        public readonly ?int $parentId,
        public readonly bool $active = false,
    )
    {
    }
}
