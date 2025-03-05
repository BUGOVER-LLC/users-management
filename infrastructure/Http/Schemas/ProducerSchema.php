<?php

declare(strict_types=1);

namespace Infrastructure\Http\Schemas;

use App\Core\Abstract\AbstractSchema;

class ProducerSchema extends AbstractSchema
{
    public function __construct(
        public readonly int $producerId,
        public readonly string $email,
        public readonly ?string $username,
    )
    {
    }
}
