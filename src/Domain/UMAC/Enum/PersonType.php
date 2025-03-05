<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Enum;

enum PersonType: string
{
    case user = 'users';

    case invitation = 'invitations';
}
