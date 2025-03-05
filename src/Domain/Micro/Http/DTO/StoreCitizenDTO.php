<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\DTO;

use App\Core\Abstract\AbstractDTO;

final class StoreCitizenDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $personType,
        public readonly ?string $patronymic,
        public readonly ?string $email,
        public readonly ?string $phone,
        public readonly ?string $dateBirth,
        public readonly ?string $gender,
        public readonly ?string $country,
        public readonly ?string $citizenship,
        public readonly ?string $documentType,
        public readonly ?string $documentValue,
        public readonly ?string $notificationAddressOrigin,
        public readonly ?int $notificationRegion,
        public readonly ?int $notificationCommunity,
        public readonly ?string $notificationAddress,
    )
    {
    }
}
