<?php

declare(strict_types=1);

namespace App\Domain\UMRP\DTO;

use App\Core\Abstract\AbstractDTO;

class CreateRoleDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $value,
        public readonly ?string $description,
        public readonly ?array $assignedPermissions,
        public readonly bool $active = true,
        public readonly bool $hasSubordinates = true,
    )
    {
    }
}
