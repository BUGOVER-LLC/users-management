<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Core\Enum\AuthGuard;
use App\Domain\UMAC\Enum\PersonType;
use App\Domain\UMAC\Http\DTO\EditUserDTO;
use App\Domain\UMAC\Model\User;
use App\Domain\UMRA\Model\Attribute;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $userId
 * @property int $parentId
 * @property string $email
 * @property int $roleId
 * @property bool $active
 * @property string $person
 * @property ?int $attributeId
 */
class EditUserRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(AuthGuard::webProducer->value)->check();
    }

    #[\Override] public function rules(): array
    {
        return [
            'userId' => [
                'required',
                'int',
                'exists:Users,userId',
            ],
            'email' => [
                'required',
                'email:rfc',
            ],
            'roleId' => [
                'required',
                'int',
                'exists:Roles,roleId',
            ],
            'attributeId' => [
                'nullable',
                'sometimes',
                'int',
                'exists:' . Attribute::getTableName() . ',' . Attribute::getPrimaryName(),
            ],
            'parentId' => [
                'nullable',
                'sometimes',
                'int',
                'exists:' . User::getTableName() . ',' . User::getPrimaryName(),
            ],
            'active' => [
                'required',
                'bool',
            ],
            'person' => [
                'sometimes',
                'string',
            ],
        ];
    }

    #[\Override] public function toDTO(): EditUserDTO
    {
        return new EditUserDTO(
            userId: $this->userId,
            email: $this->email,
            active: $this->active,
            roleId: $this->roleId,
            attributeId: $this->attributeId,
            parentId: $this->parentId,
            personType: $this->person ?? PersonType::user->value,
        );
    }
}
