<?php

declare(strict_types=1);

namespace App\Core\Enum;

use App\Core\Enum\Concerns\EnumConcern;

enum OauthClientType: string
{
    use EnumConcern;

    case password = 'password';

    case personal = 'personal';

    case public = 'public';

    case client = 'client';
}
