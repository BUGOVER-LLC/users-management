<?php

declare(strict_types=1);

namespace App\Domain\UMRA\DTO;

use App\Core\Abstract\AbstractDTO;

class CreateRoomDTO extends AbstractDTO
{
    public function __construct(
        public readonly int $attributeId,
        public readonly int $systemId,
        public readonly string $roomName,
        public readonly string $roomValue,
        public readonly ?string $roomDescription,
    )
    {
    }
}
