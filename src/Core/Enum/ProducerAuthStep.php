<?php

declare(strict_types=1);

namespace App\Core\Enum;

enum ProducerAuthStep: int
{
    case email = 1;
    case code = 2;
}
