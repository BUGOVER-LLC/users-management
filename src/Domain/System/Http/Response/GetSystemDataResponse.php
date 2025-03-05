<?php

declare(strict_types=1);

namespace App\Domain\System\Http\Response;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Core\Enum\AuthProvider;
use App\Core\Enum\OauthClientAllowedType;
use App\Core\Enum\Resource\EnumResource;
use App\Domain\System\Http\DTO\ResultSystemDataDTO;
use App\Domain\System\Http\Schema\GetSystemDataSchema;
use Illuminate\Http\Request;
use Infrastructure\Http\Resource\ClientResource;
use Infrastructure\Http\Resource\SystemResource;

/**
 * @property-read ResultSystemDataDTO $resource
 */
class GetSystemDataResponse extends AbstractResource
{
    public function toSchema(Request $request): AbstractSchema
    {
        return new GetSystemDataSchema(
            system: SystemResource::schema($this->resource->system),
            clients: ClientResource::schemas($this->resource->clients),
            clientTypes: EnumResource::schemas(OauthClientAllowedType::cases()),
            providerTypes: EnumResource::schemas(AuthProvider::cases())
        );
    }
}
