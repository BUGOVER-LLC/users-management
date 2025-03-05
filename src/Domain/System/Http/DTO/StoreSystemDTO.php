<?php

declare(strict_types=1);

namespace App\Domain\System\Http\DTO;

use App\Core\Abstract\AbstractDTO;

class StoreSystemDTO extends AbstractDTO
{
    public function __construct(
        public readonly ?string $clientName,
        public readonly ?string $clientProvider,
        public readonly ?string $clientRedirect,
        public readonly ?string $clientType,
    )
    {
    }
}
