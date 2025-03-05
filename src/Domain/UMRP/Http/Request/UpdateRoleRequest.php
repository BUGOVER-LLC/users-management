<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Core\Enum\AuthGuard;
use App\Domain\UMRP\DTO\UpdateRoleDTO;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $roleId
 * @property string $roleName
 * @property string $roleDescription
 * @property bool $roleActive
 * @property array[] $assignedPermissions
 * @property string $roleValue
 * @property bool $hasSubordinates
 * @property array $assignedAccess
 */
class UpdateRoleRequest extends AbstractRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(AuthGuard::webProducer->value)->check();
    }

    #[\Override] public function rules(): array
    {
        return [
            'roleId' => [
                'required',
                'int',
                'exists:Roles,roleId',
            ],
            'roleName' => [
                'required',
                'max:100',
                'min:3',
            ],
            'roleValue' => [
                'required',
                'max:100',
                'min:3',
            ],
            'roleDescription' => [
                'nullable',
                'max:500',
                'min:5',
            ],
            'roleActive' => [
                'bool',
            ],
            'hasSubordinates' => [
                'required',
                'bool',
            ],
            'assignedPermissions' => [
                'sometimes',
                'array',
            ],
            'assignedAccess' => [
                'nullable',
                'sometimes',
                'array',
            ],
            'createdAt' => [
                'sometimes',
                'nullable',
                'date',
                'date_format:Y-m-d H:i',
            ],
        ];
    }

    #[\Override] public function toDTO(): object
    {
        return new UpdateRoleDTO(
            roleId: $this->roleId,
            name: $this->roleName,
            value: $this->roleValue,
            description: $this->roleDescription,
            active: $this->roleActive,
            hasSubordinates: $this->hasSubordinates,
            assignedPermissions: $this->assignedPermissions,
            assignedAccess: $this->assignedAccess,
        );
    }
}
