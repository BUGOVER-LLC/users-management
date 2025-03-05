<?php

declare(strict_types=1);

namespace App\Core\Enum;

use App\Core\Enum\Concerns\EnumConcern;

enum AccessDocumentType: string
{
    use EnumConcern;

    case psn = 'psn';

    case tin = 'tin';

    case document = 'document';
}
