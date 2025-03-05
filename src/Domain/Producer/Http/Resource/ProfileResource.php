<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Domain\Producer\Http\Schema\ProfileSchema;
use App\Domain\Producer\Model\Producer;
use Illuminate\Http\Request;

/**
 * @property-read Producer $resource
 */
class ProfileResource extends AbstractResource
{
    public function toSchema(Request $request): ProfileSchema
    {
        return new ProfileSchema(
            producerId: $this->resource->producerId,
            systemId: $this->resource->currentSystemId,
            email: $this->resource->email,
            username: $this->resource->username,
        );
    }
}
