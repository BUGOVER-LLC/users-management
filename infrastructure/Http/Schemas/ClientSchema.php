<?php

declare(strict_types=1);

namespace Infrastructure\Http\Schemas;

use App\Core\Abstract\AbstractSchema;
use App\Core\Enum\OauthClientType;

class ClientSchema extends AbstractSchema
{
    public function __construct(
        public readonly int $clientId,
        public readonly int $userId,
        public readonly string $clientName,
        public readonly string $clientSecret,
        public readonly ?string $clientProvider,
        public readonly ?string $clientRedirectUrl,
        public readonly OauthClientType|string $clientType,
        public readonly ?bool $clientRevoked,
        public readonly ?string $createdAt,
    )
    {
    }
}
