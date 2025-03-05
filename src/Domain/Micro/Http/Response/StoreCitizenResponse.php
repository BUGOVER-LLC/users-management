<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Response;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Domain\Micro\Http\Schema\StoreCitizenSchema;
use App\Domain\UMAC\Model\Profile;
use Illuminate\Http\Request;

/**
 * @property-read Profile $resource
 */
class StoreCitizenResponse extends AbstractResource
{
    public function toSchema(Request $request): AbstractSchema
    {
        return new StoreCitizenSchema(
            uuid: $this->resource->citizen->uuid,
            personType: $this->resource->citizen->personType,
            email: $this->resource->citizen->email,
            isActive: $this->resource->citizen->isActive,
            psn: $this->resource?->psn,
            firstName: $this->resource->firstName,
            lastName: $this->resource->lastName,
            patronymic: $this->resource->patronymic,
            dateBirth: $this->resource->dateBirth,
            gender: $this->resource->gender,
        );
    }
}
