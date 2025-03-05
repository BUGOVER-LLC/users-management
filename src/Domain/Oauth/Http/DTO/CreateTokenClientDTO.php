<?php

declare(strict_types=1);

namespace App\Domain\Oauth\Http\DTO;

use App\Core\Abstract\AbstractDTO;
use App\Domain\Oauth\Http\Schema\BearerSchema;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[
    Schema(schema: CreateTokenClientDTO::class, type: 'object', title: 'CreateTokenClientDTO')
]
class CreateTokenClientDTO extends AbstractDTO
{
    #[Property(property: 'name', type: 'string')]
    #[Property(property: 'userId', type: 'integer')]
    #[Property(property: 'confidential', type: 'integer', nullable: true)]
    public function __construct(
        public readonly string $name,
        public readonly string|int $userId,
        public readonly ?bool $confidential,
    )
    {
    }
}
