<?php

declare(strict_types=1);

namespace Infrastructure\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Core\Enum\OauthClientType;
use App\Domain\Oauth\Model\Client;
use Illuminate\Http\Request;
use Infrastructure\Http\Schemas\ClientSchema;

/**
 * @property-read Client $resource
 */
class ClientResource extends AbstractResource
{
    public function toSchema(Request $request): AbstractSchema
    {
        return new ClientSchema(
            clientId: $this->resource->id,
            userId: $this->resource->user_id,
            clientName: $this->resource->name,
            clientSecret: OauthClientType::public->value === $this->resource->clientType->value ? $this->resource->secret : '',
            clientProvider: $this->resource->provider,
            clientRedirectUrl: $this->resource->redirect,
            clientType: $this->resource->clientType->value,
            clientRevoked: $this->resource->revoked,
            createdAt: ($this->resource->createdAt ?? null) ? $this->resource->createdAt->toISOString() : null,
        );
    }
}
