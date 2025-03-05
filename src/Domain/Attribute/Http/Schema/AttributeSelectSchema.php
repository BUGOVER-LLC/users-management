<?php

declare(strict_types=1);

namespace App\Domain\Attribute\Http\Schema;

use App\Core\Abstract\AbstractSchema;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[
    Schema(schema: AttributeSelectSchema::class, title: 'AttributeSchema')
]
class AttributeSelectSchema extends AbstractSchema
{
    #[Property(property: 'attributeName', type: 'string')]
    #[Property(property: 'attributeValue', type: 'string')]
    public function __construct(
        public readonly string $attributeName,
        public readonly string $attributeValue,
    )
    {
    }
}
