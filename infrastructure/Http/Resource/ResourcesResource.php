<?php

declare(strict_types=1);

namespace Infrastructure\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Domain\UMRA\Model\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Infrastructure\Http\Schemas\ResourcesSchema;

/**
 * @property-read Resource $resource
 */
class ResourcesResource extends AbstractResource
{
    public function toSchema(Request $request): ResourcesSchema
    {
        return new ResourcesSchema(
            $this->resource->resourceId,
            $this->resource->resourceName,
            $this->resource->resourceValue,
            $this->resource->resourceDescription,
            ($this->resource?->createdAt ?? null) ? Carbon::parse($this->resource?->createdAt)->toISOString() : null,
            ($this->resource?->updatedAt ?? null) ? Carbon::parse($this->resource?->updatedAt)->toISOString() : null
        );
    }
}
