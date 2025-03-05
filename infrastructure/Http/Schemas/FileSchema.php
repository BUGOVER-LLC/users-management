<?php

declare(strict_types=1);

namespace Infrastructure\Http\Schemas;

use App\Core\Abstract\AbstractSchema;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(schema: FileSchema::class, title: 'Files', properties: [
    new Property(property: 'name', type: 'string'),
    new Property(property: 'size', type: 'integer'),
    new Property(property: 'fileName', type: 'string'),
    new Property(property: 'path', type: 'string'),
    new Property(property: 'mimeType', type: 'string'),
    new Property(property: 'updatedAt', type: 'string'),
])]
class FileSchema extends AbstractSchema
{
    public function __construct(
        public readonly string $name,
        public readonly int $size,
        public readonly string $fileName,
        public readonly string $path,
        public readonly string $mimeType,
        public readonly string $updatedAt,
    )
    {
    }
}
