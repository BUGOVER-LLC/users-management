<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Response;

use App\Core\Abstract\AbstractDTO;
use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Core\Api\Ekeng\DTO\EkgDTO;
use App\Domain\CUM\Model\Citizen;
use App\Domain\UMAC\Http\Schema\ProfileInfoSchema;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @property-read EkgDTO|Citizen $resource
 */
class PsnInfoResource extends AbstractResource
{
    /**
     * @param Request $request
     * @return AbstractSchema
     */
    public function toSchema(Request $request): AbstractSchema
    {
        if ($this->resource instanceof AbstractDTO) {
            return new ProfileInfoSchema(
                $this->resource->psn,
                firstName: $this->resource->firstName,
                lastName: $this->resource->lastName,
                patronymic: $this->resource->patronymic,
                gender: $this->resource->gender,
                dateBirth: Carbon::parse($this->resource->dateBirth)->toDateString(),
                created: $this->resource->createdAt ?? null,
                documents: $this->resource->documents,
                personType: $this->resource->personType,
                email: $this->resource->email ?? null,
            );
        }

        return new ProfileInfoSchema(
            psn: $this->resource->profile->psn,
            firstName: $this->resource->profile->firstName,
            lastName: $this->resource->profile->lastName,
            patronymic: $this->resource->profile->patronymic,
            gender: $this->resource->profile->gender->value,
            dateBirth: Carbon::parse($this->resource->profile->dateBirth)->toDateString(),
            created: $this->resource->profile->createdAt,
            documents: $this->resource->documentFile,
            personType: $this->resource->personType,
            email: $this->resource->email,
        );
    }
}
