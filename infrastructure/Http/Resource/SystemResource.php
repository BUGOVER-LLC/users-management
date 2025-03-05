<?php

declare(strict_types=1);

namespace Infrastructure\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Domain\System\Model\System;
use Illuminate\Http\Request;
use Infrastructure\Http\Schemas\SystemSchema;

/**
 * @property-read System $resource
 */
class SystemResource extends AbstractResource
{
    public function toSchema(Request $request): AbstractSchema
    {
        return new SystemSchema(
            systemId: $this->resource->systemId,
            systemName: $this->resource->systemName,
            systemDomain: $this->resource->systemDomain,
            systemLogo: $this->resource->systemLogo ?? null,
            createdAt: ($this->resource->createdAt ?? null) ? $this->resource->createdAt->toISOString() : null,
        );
    }
}
