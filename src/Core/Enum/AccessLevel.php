<?php

declare(strict_types=1);

namespace App\Core\Enum;

enum AccessLevel: string
{
    case read = 'read';
    case write = 'write';
    case admin = 'admin';
}
