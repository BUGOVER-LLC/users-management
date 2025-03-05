<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Response;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Domain\Micro\Http\Schema\UserAccessSchema;
use App\Domain\UMAC\Model\User;
use Illuminate\Http\Request;
use Infrastructure\Http\Resource\PermissionResource;

/**
 * @property-read User $resource
 */
class UserAccessResponse extends AbstractResource
{
    public function toSchema(Request $request): AbstractSchema
    {
        return new UserAccessSchema(
            roleValue: $this->resource->role->roleValue,
            roleName: $this->resource->role->roleName,
            permissions: $this->resource?->role?->permissions ? PermissionResource::schemas(
                $this->resource->role->permissions
            ) : [],
        );
    }
}
