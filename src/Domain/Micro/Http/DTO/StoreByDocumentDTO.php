<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\DTO;

use App\Core\Abstract\AbstractDTO;
use App\Domain\CUM\Enum\AddressOriginType;

class StoreByDocumentDTO extends AbstractDTO
{
    public function __construct(
        public readonly string|int $documentValue,
        public readonly ?string $documentType,
        public readonly ?string $email,
        public readonly ?string $phone,
        public readonly null|AddressOriginType $notificationAddressOrigin,
        public readonly null|int|string $notificationRegion,
        public readonly null|int|string $notificationCommunity,
        public readonly null|string $notificationAddress,
    )
    {
    }
}
