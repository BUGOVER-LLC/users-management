<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Domain\CUM\Http\Schema\CitizenPagerSchema;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Infrastructure\Http\Schemas\AddressSchema;
use Infrastructure\Http\Schemas\CitizenSchema;
use Infrastructure\Http\Schemas\ProfileSchema;
use OpenApi\Attributes\Schema;

#[Schema(schema: CitizenResponse::class, title: 'CitizenResponse', type: CitizenPagerSchema::class)]
class CitizenResponse extends AbstractResource
{
    public function toSchema(Request $request): AbstractSchema
    {
        return new CitizenPagerSchema(
            new CitizenSchema(
                $this->resource->citizenId,
                $this->resource->isActive,
                $this->resource->isChecked,
                $this->resource->personType,
                $this->resource->email,
                $this->resource->phone,
                $this->resource->documentType,
                $this->resource->documentValue,
                $this->resource->documentFile,
            ),
            new ProfileSchema(
                $this->resource->profile->profileId,
                $this->resource->profile->psn,
                $this->resource->profile->firstName,
                $this->resource->profile->lastName,
                $this->resource->profile->patronymic,
                $this->resource->profile->gender,
                Carbon::parse($this->resource->profile->dateBirth)->toDateString(),
                $this->resource->createdAt,
            ),
            new AddressSchema(
                $this->resource->address->addressId,
                $this->resource->address->registrationRegionId,
                $this->resource->address->registrationCommunityId,
                $this->resource->address->registrationAddress,
                $this->resource->address->residenceAddressOrigin,
                $this->resource->address->residenceRegionId,
                $this->resource->address->residenceCommunityId,
                $this->resource->address->residenceAddress,
                $this->resource->address->notificationAddressOrigin,
                $this->resource->address->notificationRegionId,
                $this->resource->address->notificationCommunityId,
                $this->resource->address->notificationAddress,
            ),
        );
    }
}
