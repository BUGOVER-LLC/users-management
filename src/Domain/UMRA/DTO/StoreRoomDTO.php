<?php

declare(strict_types=1);

namespace App\Domain\UMRA\DTO;

use App\Core\Abstract\AbstractDTO;

class StoreRoomDTO extends AbstractDTO
{
    public function __construct(
        public readonly int $roomId,
        public readonly int $attributeId,
        public readonly string $roomName,
        public readonly string $roomValue,
        public readonly ?string $roomDescription,
    )
    {
    }
}
