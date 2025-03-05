<?php

declare(strict_types=1);

namespace Infrastructure\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Core\Enum\Gender;
use App\Domain\UMAC\Model\Profile;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Http\Request;
use Infrastructure\Http\Schemas\ProfileSchema;

/**
 * @property-read Profile $resource
 */
class ProfileResource extends AbstractResource
{
    /**
     * @param Request $request
     * @return ProfileSchema
     *@throws InvalidFormatException
     */
    public function toSchema(Request $request): ProfileSchema
    {
        if ($this->resource instanceof AbstractSchema) {
            return $this->resource;
        }

        return new ProfileSchema(
            $this->resource->profileId,
            $this->resource->psn,
            $this->resource->firstName,
            $this->resource->lastName,
            $this->resource->patronymic,
            Gender::fromValue($this->resource->gender),
            $this->resource->dateBirth,
            Carbon::parse($this->resource->createdAt)->toDateTimeString(),
        );
    }
}
