<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\Schema;

use App\Core\Abstract\AbstractSchema;

class AcceptCodeSchema extends AbstractSchema
{
    public function __construct(
        public readonly string $email,
        public readonly int $state,
    )
    {
    }
}
