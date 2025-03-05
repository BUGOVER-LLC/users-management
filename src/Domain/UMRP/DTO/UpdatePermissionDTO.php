<?php

declare(strict_types=1);

namespace App\Domain\UMRP\DTO;

use App\Core\Abstract\AbstractDTO;

class UpdatePermissionDTO extends AbstractDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $value,
        public readonly ?string $description = null,
        public readonly bool $active = false,
    )
    {
    }
}
