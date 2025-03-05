<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Http\Schema;

use App\Core\Abstract\AbstractSchema;
use App\Domain\Producer\Http\Schema\ProfileSchema;
use Infrastructure\Http\Schemas\InvitationUserSchema;
use Infrastructure\Http\Schemas\UserSchema;

class UserPagerSchema extends AbstractSchema
{
    public function __construct(
        public readonly UserSchema|AbstractSchema $user,
        public readonly InvitationUserSchema|ProfileSchema|AbstractSchema $profile,
    )
    {
    }
}
