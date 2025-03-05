<?php

declare(strict_types=1);

namespace Infrastructure\Http\Schemas;

use App\Core\Abstract\AbstractSchema;

class PermissionSchema extends AbstractSchema
{
    /**
     * @param int $permissionId
     * @param string $permissionName
     * @param string $permissionValue
     * @param string|null $permissionDescription
     * @param bool $permissionActive
     */
    public function __construct(
        public readonly int $permissionId,
        public readonly string $permissionName,
        public readonly string $permissionValue,
        public readonly ?string $permissionDescription,
        public readonly bool $permissionActive = true,
        public readonly array $access = [],
    )
    {
    }
}
