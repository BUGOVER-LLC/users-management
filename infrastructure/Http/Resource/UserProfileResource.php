<?php

declare(strict_types=1);

namespace Infrastructure\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Domain\CUM\Model\Citizen;
use App\Domain\UMAC\Model\Profile;
use App\Domain\UMAC\Model\User;
use Illuminate\Http\Request;
use Infrastructure\Http\Schemas\UserProfileSchema;

/**
 * @property-read User<Profile>|Citizen<Profile> $resource
 */
class UserProfileResource extends AbstractResource
{
    public function toSchema(Request $request): AbstractSchema
    {
        return new UserProfileSchema(
            userId: $this->resource->{$this->resource->getKeyName()},
            email: $this->resource->email,
            psn: $this->resource->profile->psn,
            documentType: $this->resource?->documentType ?? null,
            documentValue: $this->resource?->documentValue ?? null,
            firstName: $this->resource->profile->firstName,
            lastName: $this->resource->profile->lastName,
            patronymic: $this->resource->profile->patronymic,
            gender: $this->resource->profile->gender,
            roleName: $this->resource?->role?->roleName ?? null
        );
    }
}
