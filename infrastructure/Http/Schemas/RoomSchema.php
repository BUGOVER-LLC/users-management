<?php

declare(strict_types=1);

namespace Infrastructure\Http\Schemas;

use App\Core\Abstract\AbstractSchema;

class RoomSchema extends AbstractSchema
{
    public function __construct(
        public readonly int $roomId,
        public readonly int $attributeId,
        public readonly string $roomName,
        public readonly string $roomValue,
        public readonly ?string $roomDescription,
        public readonly bool $roomActive = true,
    )
    {
    }
}
