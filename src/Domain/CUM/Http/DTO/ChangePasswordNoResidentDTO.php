<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\DTO;

use App\Core\Abstract\AbstractDTO;
use OpenApi\Attributes\Schema;

#[
    Schema(schema: ChangePasswordNoResidentDTO::class, title: 'ChangePasswordNoResidentDTO', type: 'object')
]
class ChangePasswordNoResidentDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $code,
        public readonly string $password,
        public readonly string $passwordConfirmation,
    )
    {
    }
}
