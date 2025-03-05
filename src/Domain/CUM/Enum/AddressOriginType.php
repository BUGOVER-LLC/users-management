<?php

declare(strict_types=1);

namespace App\Domain\CUM\Enum;

use App\Core\Contracts\HasLabel;
use App\Core\Enum\Concerns\EnumConcern;

enum AddressOriginType: string implements HasLabel
{
    use EnumConcern;

    case SAME_AS_REGISTRATION_ADDRESS = 'same_as_registration_address';

    case SAME_AS_RESIDENCE_ADDRESS = 'same_as_residence_address';

    case OTHER = 'other';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SAME_AS_REGISTRATION_ADDRESS => __('enum.address_origin.same_as_registration_address'),
            self::SAME_AS_RESIDENCE_ADDRESS => __('enum.address_origin.same_as_residence_address'),
            self::OTHER => __('enum.address_origin.other'),
        };
    }
}
