<?php

declare(strict_types=1);

namespace App\Core\Enum;

use App\Core\Contracts\HasLabel;
use App\Core\Enum\Concerns\EnumConcern;

enum Accessor: string implements HasLabel
{
    use EnumConcern;

    case create = 'create';
    case read = 'read';
    case update = 'update';
    case delete = 'delete';
    case copy = 'copy';

    #[\Override] public function getLabel(): ?string
    {
    }
}
