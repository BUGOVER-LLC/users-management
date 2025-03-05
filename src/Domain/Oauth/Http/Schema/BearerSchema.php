<?php

declare(strict_types=1);

namespace App\Domain\Oauth\Http\Schema;

use App\Core\Abstract\AbstractSchema;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[
    Schema(schema: BearerSchema::class, title: 'BearerSchema', properties: [
        new Property(property: 'expiresIn', type: 'int'),
        new Property(property: 'tokenType', type: 'string'),
        new Property(property: 'accessToken', type: 'string'),
        new Property(property: 'accessUuid', type: 'string'),
        new Property(property: 'refreshToken', type: 'string'),
    ], type: 'object')
]
class BearerSchema extends AbstractSchema
{
    public function __construct(
        public readonly int|float $expiresIn,
        public readonly string $tokenType,
        public readonly string $accessToken,
        public readonly string $accessUuid,
        public readonly string $refreshToken,
    )
    {
    }
}
