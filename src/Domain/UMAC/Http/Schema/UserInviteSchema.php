<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Http\Schema;

use App\Core\Abstract\AbstractSchema;
use App\Domain\UMAC\Http\Response\InvitationUserResource;
use Infrastructure\Http\Schemas\UserSchema;

class UserInviteSchema extends AbstractSchema
{
    public function __construct(
        public readonly UserSchema|AbstractSchema $user,
        public readonly InvitationUserResource|AbstractSchema $invitation,
    ) {
    }
}
