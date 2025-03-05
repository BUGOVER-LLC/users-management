<?php

declare(strict_types=1);

namespace App\Core\Enum\Schema;

use App\Core\Abstract\AbstractSchema;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(schema: EnumSchema::class, title: 'EnumSchema', properties: [
    new Property(property: 'name', type: 'string'),
    new Property(property: 'value', type: ['string', 'integer']),
])]
class EnumSchema extends AbstractSchema
{
    /**
     * @param string|int $name
     * @param string|int $value
     * @param bool|null $selected
     */
    public function __construct(
        public readonly string|int $name,
        public readonly string|int $value,
        public readonly ?bool $selected = null,
    )
    {
    }
}
