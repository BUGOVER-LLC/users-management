<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\DTO;

use App\Core\Abstract\AbstractDTO;

class NidTokenDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $uuid,
        public readonly ?string $tokenMachine = null,
    )
    {
    }
}
