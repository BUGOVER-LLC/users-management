<?php

declare(strict_types=1);

namespace Infrastructure\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Domain\UMRP\Model\Permission;
use Illuminate\Http\Request;
use Infrastructure\Http\Schemas\PermissionSchema;

/**
 * @property Permission $resource
 */
class PermissionResource extends AbstractResource
{
    public function toSchema(Request $request): AbstractSchema
    {
        return new PermissionSchema(
            permissionId: $this->resource->permissionId,
            permissionName: $this->resource->permissionName,
            permissionValue: $this->resource->permissionValue,
            permissionDescription: $this->resource->permissionDescription,
            permissionActive: $this->resource->permissionActive,
            access: $this->resource?->pivot?->access ?? [] ? explode(',', $this->resource->pivot->access) : [],
        );
    }
}
