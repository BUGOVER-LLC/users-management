<?php

declare(strict_types=1);

namespace Infrastructure\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Domain\UMRA\Model\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Infrastructure\Http\Schemas\AttributeSchema;

/**
 * @property-read Attribute $resource
 */
class AttributeResource extends AbstractResource
{
    public function toSchema(Request $request): AttributeSchema
    {
        return new AttributeSchema(
            attributeId: $this->resource->attributeId,
            attributeName: $this->resource->attributeName,
            resourceId: $this->resource->resourceId,
            attributeValue: $this->resource->attributeValue,
            attributeDescription: $this->resource->attributeDescription,
            createdAt: ($this->resource?->createdAt ?? null) ? Carbon::parse($this->resource?->createdAt)->toISOString() : null,
            updatedAt: ($this->resource?->updatedAt ?? null) ? Carbon::parse($this->resource?->updatedAt)->toISOString() : null
        );
    }
}
