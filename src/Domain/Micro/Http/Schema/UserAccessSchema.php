<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Schema;

use App\Core\Abstract\AbstractSchema;

class UserAccessSchema extends AbstractSchema
{
    public function __construct(
        public readonly ?string $roleValue,
        public readonly ?string $roleName,
        public readonly ?array $permissions,
    )
    {
    }
}
