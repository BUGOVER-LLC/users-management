<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Domain\Producer\Http\Schema\AcceptCodeSchema;
use Illuminate\Http\Request;

class AcceptCodeResource extends AbstractResource
{
    public function toSchema(Request $request): AbstractSchema
    {
        if ($this->resource instanceof AbstractSchema) {
            return $this->resource;
        }

        return new AcceptCodeSchema(
            $this->resource->email,
            $this->resource->state,
        );
    }
}
