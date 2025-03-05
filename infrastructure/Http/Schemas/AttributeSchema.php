<?php

declare(strict_types=1);

namespace Infrastructure\Http\Schemas;

use App\Core\Abstract\AbstractSchema;

class AttributeSchema extends AbstractSchema
{
    public function __construct(
        public readonly int $attributeId,
        public readonly string $attributeName,
        public readonly ?int $resourceId,
        public readonly string $attributeValue,
        public readonly ?string $attributeDescription,
        public readonly ?string $createdAt,
        public readonly ?string $updatedAt,
    )
    {
    }
}
