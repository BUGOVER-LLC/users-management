<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Http\Response;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Domain\UMAC\Http\Schema\ProfileInfoSchema;
use App\Domain\UMAC\Http\Schema\UserPagerSchema;
use App\Domain\UMAC\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Infrastructure\Http\Schemas\InvitationUserSchema;
use Infrastructure\Http\Schemas\ProfileSchema;
use Infrastructure\Http\Schemas\UserSchema;

/**
 * @property-read User $resource
 */
class UserPagerResponse extends AbstractResource
{
    public function toSchema(Request $request): AbstractSchema
    {
        return new UserPagerSchema(
            new UserSchema(
                userId: $this->resource->userId,
                uuid: $this->resource->uuid,
                roleId: $this->resource->roleId,
                profileId: $this->resource->profileId,
                active: $this->resource->active,
                email: $this->resource->email,
                attributeId: $this->resource->attributeId,
                parentId: $this->resource->parentId,
            ),
            profile: $this->resource->profile ? new ProfileSchema(
                profileId: $this->resource->profile->profileId,
                psn: $this->resource->profile->psn,
                firstName: $this->resource->profile->firstName,
                lastName: $this->resource->profile->lastName,
                patronymic: $this->resource->profile->patronymic,
                gender: $this->resource->profile->gender,
                dateBirth: Carbon::parse($this->resource->profile->dateBirth)->toDateString(),
                createdAt: Carbon::parse($this->resource->profile->createdAt)->toDateString()
            ) : new InvitationUserSchema(
                userId: $this->resource->invitation->userId,
                inviteUrl: $this->resource->invitation->inviteUrl,
                passed: $this->resource->invitation->passed,
                createdAt: $this->resource->invitation->createdAt,
                psnInfo: new ProfileInfoSchema(
                    psn: $this->resource->invitation->psnInfo['psn'],
                    firstName: $this->resource->invitation->psnInfo['firstName'],
                    lastName: $this->resource->invitation->psnInfo['lastName'],
                    patronymic: $this->resource->invitation->psnInfo['patronymic'],
                    gender: $this->resource->invitation->psnInfo['gender'],
                    dateBirth: $this->resource->invitation->psnInfo['dateBirth'],
                ),
            )
        );
    }
}
