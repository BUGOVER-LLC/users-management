<?php

declare(strict_types=1);

namespace Infrastructure\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Domain\UMAC\Model\User;
use Illuminate\Http\Request;
use Infrastructure\Http\Schemas\UserSchema;

/**
 * @property-read User $resource
 */
class UserResource extends AbstractResource
{
    /**
     * @param Request $request
     * @return UserSchema
     */
    public function toSchema(Request $request): UserSchema
    {
        return new UserSchema(
            userId: $this->resource->userId,
            uuid: $this->resource->uuid,
            roleId: $this->resource->roleId,
            profileId: $this->resource->profileId,
            active: $this->resource->active,
            email: $this->resource->email,
        );
    }
}
