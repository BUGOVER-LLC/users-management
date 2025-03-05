<?php

declare(strict_types=1);

namespace App\Core\Enum;

enum SwaggerTags: string
{
    case auth = 'Auth';

    case editProfile = 'Edit profile';

    case getData = 'Get';
}
