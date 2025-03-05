<?php

declare(strict_types=1);

namespace App\Core\FileSystem\Enum;

use App\Core\Enum\Concerns\EnumConcern;

enum FileCategoryEnum: string
{
    use EnumConcern;

    case FILE = 'file';
    case IMAGE = 'image';
    case AUDIO = 'audio';
}
