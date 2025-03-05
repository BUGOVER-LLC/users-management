<?php

declare(strict_types=1);

namespace App\Core\Enum;

use App\Core\Enum\Concerns\EnumConcern;

enum PersonType: string
{
    use EnumConcern;

    case RESIDENT = 'resident';

    case NON_RESIDENT = 'noneResident';

    case UNDEFINED = 'undefined';
}
