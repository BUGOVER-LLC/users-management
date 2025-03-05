<?php

declare(strict_types=1);

namespace Infrastructure\Http\DTO;

use App\Core\Abstract\AbstractDTO;

class DefaultPaginateDTO extends AbstractDTO
{
    public function __construct(
        public int $page,
        public int $perPage,
        public ?string $search,
    )
    {
    }
}
