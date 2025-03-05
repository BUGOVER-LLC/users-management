<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Http\Response;

use App\Core\Abstract\AbstractResource;
use App\Domain\UMAC\Http\Schema\UserInviteSchema;
use App\Domain\UMAC\Model\User;
use Illuminate\Http\Request;
use Infrastructure\Http\Resource\UserResource;

/**
 * @property-read User $resource
 */
class UserInviteResponse extends AbstractResource
{
    public function toSchema(Request $request): UserInviteSchema
    {
        return new UserInviteSchema(
            UserResource::schema($this->resource),
            InvitationUserResource::schema($this->resource->invitation),
        );
    }
}
