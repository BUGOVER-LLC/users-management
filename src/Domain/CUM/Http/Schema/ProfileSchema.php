<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Schema;

use App\Core\Abstract\AbstractSchema;
use App\Domain\CUM\Enum\AddressOriginType;

final class ProfileSchema extends AbstractSchema
{
    public function __construct(
        public readonly bool $hasRegistrationAddress,
        public readonly string $fullName,
        public readonly null|string $psn,
        public readonly null|string $birthDate,
        public readonly null|string|bool $registrationAddress,
        public readonly null|string $phone,
        public readonly null|string $email,
        public readonly null|bool|AddressOriginType $notificationAddressOrigin,
        public readonly null|int|bool $notificationRegion,
        public readonly null|int|bool $notificationCommunity,
        public readonly null|string|bool $notificationAddress,
        public readonly null|string $avatar = null,
        public readonly bool $canUpdateAddress = true,
    ) {}
}
