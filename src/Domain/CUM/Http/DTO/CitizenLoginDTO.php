<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\DTO;

use App\Core\Abstract\AbstractDTO;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[
    Schema(schema: CitizenLoginDTO::class, properties: [
        new Property(property: 'email', type: 'string'),
        new Property(property: 'password', type: 'string'),
    ], type: 'object')
]
class CitizenLoginDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    )
    {
    }
}
