<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\DTO;

use App\Core\Abstract\AbstractDTO;

class NidDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $state,
        public readonly string $code,
    )
    {
    }
}
