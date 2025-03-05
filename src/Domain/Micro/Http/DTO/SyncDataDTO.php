<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\DTO;

use App\Core\Abstract\AbstractDTO;

class SyncDataDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $type,
        public readonly bool $force,
        public readonly array $params
    )
    {
    }
}
