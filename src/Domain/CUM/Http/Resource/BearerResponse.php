<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Domain\CUM\Http\Schema\BearerSchema;
use Illuminate\Http\Request;

class BearerResponse extends AbstractResource
{
    public function toSchema(Request $request): BearerSchema
    {
        return new BearerSchema(
            $this->resource['expiresIn'],
            $this->resource['tokenType'],
            $this->resource['accessToken'],
            $this->resource['uuid'],
            $this->resource['refreshToken'] ?? '',
        );
    }
}
