<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\Schema;

use App\Core\Abstract\AbstractSchema;

class ProfileSchema extends AbstractSchema
{
    public function __construct(
        public readonly int $producerId,
        public readonly int $systemId,
        public readonly string $email,
        public readonly ?string $username,
    )
    {
    }
}
