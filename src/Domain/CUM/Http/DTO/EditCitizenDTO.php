<?php

namespace App\Domain\CUM\Http\DTO;

use App\Core\Abstract\AbstractDTO;
use App\Domain\CUM\Model\Citizen;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[
    Schema(schema: EditCitizenDTO::class, title: 'EditCitizenDTO', type: 'object')
]
class EditCitizenDTO extends AbstractDTO
{
    #[Property(property: 'email', type: 'string')]
    #[Property(property: 'phone', type: 'string')]
    #[Property(property: 'password', type: 'string')]
    #[Property(property: 'passwordConfirmation', type: 'string')]
    public function __construct(
        public readonly int $citizenId,
        public readonly string $email,
        public readonly string $phone,
        public readonly string $password,
        public readonly string $passwordConfirmation
    )
    {
    }

}
