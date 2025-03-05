<?php

declare(strict_types=1);

namespace App\Core\Enum;

use App\Core\Enum\Concerns\EnumConcern;

enum DocumentStatus: string
{
    use EnumConcern;

    case VALID = 'VALID';

    case INVALID = 'INVALID';

    case PRIMARY_VALID = 'PRIMARY_VALID';
}
