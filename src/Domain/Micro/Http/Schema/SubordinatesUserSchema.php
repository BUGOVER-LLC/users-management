<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Schema;

use App\Core\Abstract\AbstractSchema;

class SubordinatesUserSchema extends AbstractSchema
{
    public function __construct(
        public readonly int $userId,
        public readonly string $uuid,
        public readonly string $roleValue,
    )
    {
    }
}
