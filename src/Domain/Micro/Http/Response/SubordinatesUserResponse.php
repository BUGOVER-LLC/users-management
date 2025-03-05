<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Response;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Domain\Micro\Http\Schema\SubordinatesUserSchema;
use App\Domain\UMAC\Model\Profile;
use App\Domain\UMAC\Model\User;
use App\Domain\UMRP\Model\Role;
use Illuminate\Http\Request;

/**
 * @property-read User<Role|Profile> $resource
 */
class SubordinatesUserResponse extends AbstractResource
{
    public function toSchema(Request $request): AbstractSchema
    {
        return new SubordinatesUserSchema(
            userId: $this->resource->userId,
            uuid: $this->resource->uuid,
            roleValue: $this->resource->role->roleValue
        );
    }
}
