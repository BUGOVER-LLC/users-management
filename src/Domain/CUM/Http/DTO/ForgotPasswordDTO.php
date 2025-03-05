<?php

namespace App\Domain\CUM\Http\DTO;

use App\Core\Abstract\AbstractDTO;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[
    Schema(schema: ForgotPasswordDTO::class, type :'object', title: 'ForgotPasswordDTO')
]
class ForgotPasswordDTO extends AbstractDTO
{
    #[Property(property: 'email', type: 'string')]
    public function __construct(
        public readonly string $email
    ){}

}
