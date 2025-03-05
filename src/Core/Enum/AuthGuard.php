<?php

declare(strict_types=1);

namespace App\Core\Enum;

enum AuthGuard: string
{
    case webProducer = 'web_producer';
    case apiUsers = 'api_users';
    case apiCitizens = 'api_citizens';
}
