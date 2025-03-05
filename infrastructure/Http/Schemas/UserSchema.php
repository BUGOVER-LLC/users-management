<?php

declare(strict_types=1);

namespace Infrastructure\Http\Schemas;

use App\Core\Abstract\AbstractSchema;

class UserSchema extends AbstractSchema
{
    public function __construct(
        public readonly int $userId,
        public readonly ?string $uuid,
        public readonly ?int $roleId,
        public readonly ?int $profileId,
        public readonly bool $active,
        public readonly string $email,
        public readonly ?int $attributeId = null,
        public readonly ?int $parentId = null,
    ) {
    }
}
