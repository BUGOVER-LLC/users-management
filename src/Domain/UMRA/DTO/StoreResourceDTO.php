<?php

declare(strict_types=1);

namespace App\Domain\UMRA\DTO;

use App\Core\Abstract\AbstractDTO;

class StoreResourceDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $resourceName,
        public readonly string $resourceValue,
        public readonly ?string $resourceDescription,
        public readonly ?int $resourceId = null,
    )
    {
    }
}
