<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Schema;

use App\Core\Abstract\AbstractSchema;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(title: 'BearerSchema', required: [
    'expiresIn',
    'tokenType',
    'accessToken',
    'accessUuid',
    'refreshToken',
], properties: [
    new Property(property: 'expiresIn', type: 'integer'),
    new Property(property: 'tokenType', type: 'string'),
    new Property(property: 'accessToken', type: 'string'),
    new Property(property: 'accessUuid', type: 'string'),
    new Property(property: 'refreshToken', type: 'string'),
])]
class BearerSchema extends AbstractSchema
{
    public function __construct(
        public readonly int|float $expiresIn,
        public readonly string $tokenType,
        public readonly string $accessToken,
        public readonly string $accessUuid,
        public readonly null|string $refreshToken,
    )
    {
    }
}
