<?php

declare(strict_types=1);

namespace Infrastructure\Http\Schemas;

use App\Core\Abstract\AbstractSchema;

class ResourcesSchema extends AbstractSchema
{
    public function __construct(
        public readonly int $resourceId,
        public readonly string $resourceName,
        public readonly string $resourceValue,
        public readonly ?string $resourceDescription,
        public readonly ?string $createdAt,
        public readonly ?string $updatedAt,
    )
    {
    }
}
