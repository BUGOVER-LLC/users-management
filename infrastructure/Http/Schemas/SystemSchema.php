<?php

declare(strict_types=1);

namespace Infrastructure\Http\Schemas;

use App\Core\Abstract\AbstractSchema;

class SystemSchema extends AbstractSchema
{
    public function __construct(
        public readonly int $systemId,
        public readonly string $systemName,
        public readonly ?string $systemDomain,
        public readonly ?array $systemLogo,
        public readonly ?string $createdAt,
    )
    {
    }
}
