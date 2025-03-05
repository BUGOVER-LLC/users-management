<?php

declare(strict_types=1);

namespace App\Core\Enum;

use App\Core\Enum\Concerns\EnumConcern;

enum AuthProvider: string
{
    use EnumConcern;

    case producer = 'producers';

    case users = 'users';

    case citizens = 'citizens';
}
