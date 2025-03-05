<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Domain\CUM\Http\Schema\BearerSchema;
use Illuminate\Http\Request;

class PersonalAccessResponse extends AbstractResource
{
    /**
     * @inheritDoc
     */
    public function toSchema(Request $request): BearerSchema
    {
        return new BearerSchema(
            expiresIn: $this->resource['expiresIn'],
            tokenType: $this->resource['tokenType'],
            accessToken: $this->resource['accessToken'],
            accessUuid: $this->resource['uuid'],
            refreshToken: $this->resource['refreshToken'],
        );
    }
}
