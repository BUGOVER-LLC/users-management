<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\DTO;

use App\Core\Abstract\AbstractDTO;
use App\Domain\CUM\Enum\AddressOriginType;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[
    Schema(schema: ProfileUpdateDTO::class, title: 'ProfileUpdateDTO', required: [
        'userId',
        'phone',
        'notificationAddressOrigin',
    ], properties: [
        new Property(property: 'userId', type: 'int', nullable: false),
        new Property(property: 'phone', type: 'string', nullable: false),
        new Property(property: 'email', type: 'string', nullable: false),
        new Property(property: 'notificationAddressOrigin', type: 'string', nullable: true),
        new Property(property: 'notificationRegion', type: 'int', nullable: true),
        new Property(property: 'notificationCommunity', type: 'int', nullable: true),
        new Property(property: 'notificationAddress', type: 'string', nullable: true),
    ])
]
class ProfileUpdateDTO extends AbstractDTO
{
    public function __construct(
        public readonly int $citizenId,
        public readonly string $email,
        public readonly string $phone,
        public readonly null|AddressOriginType $notificationAddressOrigin,
        public readonly null|string|int $notificationRegion,
        public readonly null|string|int $notificationCommunity,
        public readonly null|string $notificationAddress,
    )
    {
    }
}
