<?php

declare(strict_types=1);

namespace Infrastructure\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Domain\UMRP\Model\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Infrastructure\Http\Schemas\RolesSchema;

/**
 * @property Role $resource
 */
class RoleResource extends AbstractResource
{
    public function toSchema(Request $request): AbstractSchema
    {
        return new RolesSchema(
            roleId: $this->resource->roleId,
            roleName: $this->resource->roleName,
            roleValue: $this->resource->roleValue,
            roleDescription: $this->resource->roleDescription,
            roleActive: $this->resource->roleActive,
            hasSubordinates: $this->resource->hasSubordinates,
            createdAt: Carbon::parse($this->resource->createdAt)->format('Y-m-d H:i'),
        );
    }
}
