<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Http\DTO;

use App\Core\Abstract\AbstractDTO;
use App\Domain\UMAC\Enum\PersonType;

class EditUserDTO extends AbstractDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly string $email,
        public readonly bool $active,
        public readonly ?int $roleId,
        public readonly ?int $attributeId = null,
        public readonly ?int $parentId = null,
        public readonly string $personType = PersonType::user->value,
    )
    {
    }
}
