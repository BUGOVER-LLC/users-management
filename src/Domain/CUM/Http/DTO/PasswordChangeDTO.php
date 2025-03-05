<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\DTO;

use App\Core\Abstract\AbstractDTO;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[
    Schema(schema: PasswordChangeDTO::class, type: 'object', title: 'PasswordChangeDTO')
]
class PasswordChangeDTO extends AbstractDTO
{
    #[Property(property: 'citizenId', type: 'int', nullable: false)]
    #[Property(property: 'password', type: 'string', nullable: false)]
    #[Property(property: 'passwordConfirmation', type: 'string', nullable: false)]
    public function __construct(
        public readonly int $citizenId,
        public readonly string $password,
        public readonly string $passwordConfirmation,
    )
    {
    }
}
