<?php

declare(strict_types=1);

namespace Infrastructure\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Domain\Producer\Model\Producer;
use Illuminate\Http\Request;
use Infrastructure\Http\Schemas\ProducerSchema;

/**
 * @property-read Producer $resource
 */
class ProducerResource extends AbstractResource
{
    /**
     * @param Request $request
     * @return AbstractSchema
     */
    public function toSchema(Request $request): AbstractSchema
    {
        return new ProducerSchema(
            producerId: $this->resource->producerId,
            email: $this->resource->email,
            username: $this->resource->username
        );
    }
}
