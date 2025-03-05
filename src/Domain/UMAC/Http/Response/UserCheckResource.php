<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Http\Response;

use App\Core\Abstract\AbstractResource;
use App\Core\Api\Ekeng\DTO\EkgDTO;
use App\Domain\UMAC\Http\Schema\ProfileInfoSchema;
use App\Domain\UMAC\Model\User;
use Illuminate\Http\Request;

/**
 * @property-read User|EkgDTO $resource
 */
class UserCheckResource extends AbstractResource
{
    /**
     * @param Request $request
     * @return ProfileInfoSchema
     */
    public function toSchema(Request $request): ProfileInfoSchema
    {
        if ($this->resource instanceof EkgDTO) {
            return new ProfileInfoSchema(
                $this->resource->psn,
                $this->resource->firstName,
                $this->resource->lastName,
                $this->resource->patronymic,
                $this->resource->gender,
                $this->resource->dateBirth,
            );
        }

        return new ProfileInfoSchema(
            $this->resource->profile->psn,
            $this->resource->profile->firstName,
            $this->resource->profile->lastName,
            $this->resource->profile->patronymic,
            $this->resource->profile->gender,
            $this->resource->profile->dateBirth,
            $this->resource->profile->createdAt,
        );
    }
}
