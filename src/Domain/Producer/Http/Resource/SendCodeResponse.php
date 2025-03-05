<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Domain\System\Model\System;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Infrastructure\Http\Resource\SystemResource;

/**
 * @property-read Collection<System> $resource
 */
class SendCodeResponse extends AbstractResource
{
    public function toSchema(Request $request): AbstractSchema
    {
        return SystemResource::schema($this->resource);
    }
}
