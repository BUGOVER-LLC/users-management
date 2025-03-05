<?php

declare(strict_types=1);

namespace App\Domain\UMRA\DTO;

use App\Core\Abstract\AbstractDTO;

class StoreAttributeDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $attributeName,
        public readonly string $attributeValue,
        public readonly ?string $attributeDescription,
        public readonly ?int $resourceId,
        public readonly ?int $attributeId = null,
    )
    {
    }
}
