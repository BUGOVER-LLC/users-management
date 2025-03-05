<?php

declare(strict_types=1);

namespace App\Domain\Oauth\Http\Response;

use App\Core\Abstract\AbstractResource;
use App\Domain\Oauth\Http\Schema\BearerSchema;
use Illuminate\Http\Request;

/**
 * Class BearerResource
 *
 * @property mixed token_type
 * @property mixed expires_in
 * @property mixed access_token
 * @property mixed refresh_token
 * @package Src\Http\Resources
 */
class BearerResponse extends AbstractResource
{
    /**
     * @param Request $request
     * @return BearerSchema
     */
    public function toSchema(Request $request): BearerSchema
    {
        return new BearerSchema(
            $this->resource['expiresIn'],
            $this->resource['tokenType'],
            $this->resource['accessToken'],
            $this->resource['uuid'],
            $this->resource['refreshToken'],
        );
    }
}
