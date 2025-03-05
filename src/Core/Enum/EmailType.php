<?php

declare(strict_types=1);

namespace App\Core\Enum;

enum EmailType: string
{
    case acceptCode = 'acceptCode';
    case inviteUser = 'inviteUser';
}
