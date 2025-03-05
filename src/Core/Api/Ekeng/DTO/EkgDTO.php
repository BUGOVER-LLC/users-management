<?php

declare(strict_types=1);

namespace App\Core\Api\Ekeng\DTO;

use App\Core\Abstract\AbstractDTO;
use App\Core\Enum\PersonType;

final class EkgDTO extends AbstractDTO
{
    public function __construct(
        public readonly string|int $psn,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly ?string $patronymic,
        public readonly string $gender,
        public readonly string $dateBirth,
        public readonly ?string $deathDate,
        public readonly ?string $region,
        public readonly ?string $community,
        public readonly ?string $registrationAddress,
        public readonly array|EkgPassportDTO $documents = [],
        public readonly ?PersonType $personType = null,
        public readonly null|string|int $identifierDocumentType = null,
    )
    {
    }
}
