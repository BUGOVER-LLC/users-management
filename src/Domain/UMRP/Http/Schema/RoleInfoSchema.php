<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Http\Schema;

use App\Core\Abstract\AbstractSchema;
use App\Core\Enum\Schema\EnumSchema;
use Infrastructure\Http\Schemas\PermissionSchema;
use Infrastructure\Http\Schemas\RolesSchema;

class RoleInfoSchema extends AbstractSchema
{
    public function __construct(
        public readonly RolesSchema|AbstractSchema $role,
        public readonly PermissionSchema|AbstractSchema|array $selectedPermissions,
        public readonly PermissionSchema|AbstractSchema|array $availablePermissions,
        public readonly EnumSchema|array $accessors,
    ) {
    }
}
