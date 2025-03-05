<?php

declare(strict_types=1);

namespace Infrastructure\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Domain\UMAC\Model\InvitationUser;
use Illuminate\Http\Request;
use Infrastructure\Http\Schemas\UserInvitationSchema;

/**
 * @property-read InvitationUser $resource
 */
class UserInvitationResource extends AbstractResource
{
    public function toSchema(Request $request): AbstractSchema
    {
        return new UserInvitationSchema(
            $this->resource->invitationUserId,
            $this->resource->userId,
            $this->resource->inviteUrl,
            $this->resource->inviteEmail,
            ($this->resource->passed ?? null) ? $this->resource->passed->toISOString() : null,
            ($this->resource->acceptedAt ?? null) ? $this->resource->acceptedAt->toISOString() : null,
        );
    }
}
