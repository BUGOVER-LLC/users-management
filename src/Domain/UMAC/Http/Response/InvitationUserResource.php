<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Http\Response;

use App\Core\Abstract\AbstractResource;
use App\Domain\UMAC\Http\Schema\ProfileInfoSchema;
use App\Domain\UMAC\Model\InvitationUser;
use Illuminate\Http\Request;
use Infrastructure\Http\Schemas\InvitationUserSchema;

/**
 * @property-read InvitationUser $resource
 */
class InvitationUserResource extends AbstractResource
{
    public function toSchema(Request $request): InvitationUserSchema
    {
        return new InvitationUserSchema(
            $this->resource->userId,
            $this->resource->inviteUrl,
            $this->resource->passed,
            $this->resource->createdAt,
            new ProfileInfoSchema(
                $this->resource->psnInfo['psn'],
                $this->resource->psnInfo['firstName'],
                $this->resource->psnInfo['lastName'],
                $this->resource->psnInfo['patronymic'],
                $this->resource->psnInfo['gender'],
                $this->resource->psnInfo['dateBirth'],
            ),
        );
    }
}
