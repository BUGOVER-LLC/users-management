<?php

declare(strict_types=1);

namespace App\Domain\UMAA\Http\DTO;

use App\Core\Abstract\AbstractDTO;
use OpenApi\Attributes\AdditionalProperties;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[
    Schema(schema: AcceptInviteUserDTO::class, title: 'AcceptInviteUserDTO', type: 'object')
]
class AcceptInviteUserDTO extends AbstractDTO
{
    #[Property(property: 'email', type: 'string')]
    #[Property(property: 'token', type: 'string')]
    #[Property(property: 'password', type: 'string')]
    public function __construct(
        public readonly string $email,
        public readonly string $token,
        public readonly string $password,
    ) {
    }
}
