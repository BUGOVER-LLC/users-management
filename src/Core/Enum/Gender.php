<?php

declare(strict_types=1);

namespace App\Core\Enum;

use App\Core\Enum\Concerns\EnumConcern;

enum Gender: string
{
    use EnumConcern;

    case MALE = 'M';

    case FEMALE = 'F';
}
