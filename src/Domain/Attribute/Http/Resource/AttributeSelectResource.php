<?php

declare(strict_types=1);

namespace App\Domain\Attribute\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Domain\UMRA\Model\Attribute;
use Illuminate\Http\Request;
use App\Domain\Attribute\Http\Schema\AttributeSelectSchema;

/**
 * @property-read Attribute $resource
 */
class AttributeSelectResource extends AbstractResource
{
    public function toSchema(Request $request): AbstractSchema
    {
        return new AttributeSelectSchema(
            attributeName: $this->resource->attributeName,
            attributeValue: $this->resource->attributeValue
        );
    }
}
