<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Schema;

use App\Core\Abstract\AbstractSchema;
use App\Core\Enum\PersonType;

class StoreCitizenSchema extends AbstractSchema
{
    public function __construct(
        public readonly string $uuid,
        public readonly string|PersonType $personType,
        public readonly ?string $email,
        public readonly bool $isActive,
        public readonly ?int $psn,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly ?string $patronymic,
        public readonly ?string $dateBirth,
        public readonly ?string $gender,
    )
    {
    }
}
