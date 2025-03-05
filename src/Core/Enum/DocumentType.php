<?php

declare(strict_types=1);

namespace App\Core\Enum;

use App\Core\Enum\Concerns\EnumConcern;

enum DocumentType: string
{
    use EnumConcern;

    case NON_BIOMETRIC_PASSPORT = 'NON_BIOMETRIC_PASSPORT';

    case BIRTH_CERTIFICATE = 'BIRTH_CERTIFICATE';

    case ID_CARD = 'ID_CARD';

    case BIOMETRIC_PASSPORT = 'BIOMETRIC_PASSPORT';

    case RESIDENCE_CARD = 'RESIDENCE_CARD';
}
