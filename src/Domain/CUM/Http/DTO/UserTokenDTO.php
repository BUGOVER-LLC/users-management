<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\DTO;

use App\Core\Abstract\AbstractDTO;

class UserTokenDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $uuid,
    )
    {
    }
}
