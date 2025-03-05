<?php

declare(strict_types=1);

namespace Infrastructure\Http\Schemas;

use App\Core\Abstract\AbstractSchema;

class UserInvitationSchema extends AbstractSchema
{
    public function __construct(
        public readonly int $userInvitationId,
        public readonly int $userId,
        public readonly string $inviteUrl,
        public readonly string $inviteEmail,
        public readonly string $passed,
        public readonly ?string $acceptedAt,
    )
    {
    }
}
