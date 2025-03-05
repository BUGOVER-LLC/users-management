<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Enum;

enum RedisKey: string
{
    case userPsn = 'user_psn_profile:';
    case citizenCheckPsn = 'citizen_check_psn:';
}
