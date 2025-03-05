<?php

declare(strict_types=1);

namespace Infrastructure\Http\Schemas;

use App\Core\Abstract\AbstractSchema;
use Carbon\Carbon;

class RolesSchema extends AbstractSchema
{
    /**
     * @param int $roleId
     * @param string $roleName
     * @param string $roleValue
     * @param string|null $roleDescription
     * @param bool $roleActive
     * @param Carbon|string $createdAt
     */
    public function __construct(
        public readonly int $roleId,
        public readonly string $roleName,
        public readonly string $roleValue,
        public readonly ?string $roleDescription,
        public readonly bool $roleActive,
        public readonly bool $hasSubordinates,
        public readonly Carbon|string $createdAt = '',
    )
    {
    }
}
