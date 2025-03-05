<?php

declare(strict_types=1);

namespace App\Domain\UMRP\DTO;

use Infrastructure\Http\DTO\DefaultPaginateDTO;

class GetRolesDTO extends DefaultPaginateDTO
{
    public function __construct(
        public int $page,
        public int $perPage,
        public ?string $search,
    )
    {
        parent::__construct($page, $perPage, $search);
    }
}
