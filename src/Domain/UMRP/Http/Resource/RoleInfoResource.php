<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Core\Enum\Accessor;
use App\Domain\UMRP\Http\Schema\RoleInfoSchema;
use App\Domain\UMRP\Model\Role;
use Illuminate\Http\Request;
use Infrastructure\Http\Resource\PermissionResource;
use Infrastructure\Http\Resource\RoleResource;

/**
 * @property-read Role $resource
 */
class RoleInfoResource extends AbstractResource
{
    /**
     * @param Request $request
     * @return AbstractSchema
     */
    public function toSchema(Request $request): AbstractSchema
    {
        return new RoleInfoSchema(
            role: RoleResource::schema($this->resource),
            selectedPermissions: $this->resource->permissions->count() ? PermissionResource::schemas(
                $this->resource->permissions
            ) : [],
            availablePermissions: $this->resource->freePermissions->count() ? PermissionResource::schemas(
                $this->resource->freePermissions
            ) : [],
            accessors: Accessor::all()->values()->all()
        );
    }
}
