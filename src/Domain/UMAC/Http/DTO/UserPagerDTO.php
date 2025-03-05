<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Http\DTO;

use Infrastructure\Http\DTO\DefaultPaginateDTO;

class UserPagerDTO extends DefaultPaginateDTO
{
    public function __construct(
        public int $page,
        public int $perPage,
        public ?string $search,
        public ?string $personType,
        public ?array $roles = null,
        public ?bool $active = false,
    )
    {
        parent::__construct($page, $perPage, $search);
    }
}
