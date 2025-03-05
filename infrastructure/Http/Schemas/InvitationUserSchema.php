<?php

declare(strict_types=1);

namespace Infrastructure\Http\Schemas;

use App\Core\Abstract\AbstractSchema;
use App\Domain\UMAC\Http\Schema\ProfileInfoSchema;
use Carbon\Carbon;

class InvitationUserSchema extends AbstractSchema
{
    public function __construct(
        public readonly int $userId,
        public readonly string $inviteUrl,
        public readonly string|Carbon $passed,
        public readonly string|Carbon $createdAt,
        public readonly ProfileInfoSchema $psnInfo,
    )
    {
    }
}
