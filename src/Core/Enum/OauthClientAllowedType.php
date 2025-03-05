<?php

declare(strict_types=1);

namespace App\Core\Enum;

use App\Core\Enum\Concerns\EnumConcern;

enum OauthClientAllowedType: string
{
    use EnumConcern;

    case password = 'password';

    case public = 'public';

    case client = 'client';
}
