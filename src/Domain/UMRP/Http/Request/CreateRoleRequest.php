<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Core\Enum\AuthGuard;
use App\Domain\UMRP\DTO\CreateRoleDTO;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Illuminate\Support\Rule\CheckRoleUniqueInSystemEnv;

class CreateRoleRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(AuthGuard::webProducer->value)->check();
    }

    #[\Override] public function rules(): array
    {
        return [
            'roleName' => [
                'required',
                'max:100',
                'min:3',
                new CheckRoleUniqueInSystemEnv($this->user()->currentSystemId),
            ],
            'roleValue' => [
                'required',
                'max:100',
                'min:3',
                new CheckRoleUniqueInSystemEnv($this->user()->currentSystemId),
            ],
            'roleDescription' => [
                'nullable',
                'sometimes',
                'max:500',
                'min:5',
            ],
            'roleActive' => [
                'required',
                'bool',
            ],
            'hasSubordinates' => [
                'required',
                'bool',
            ],
            'roleId' => [
                'nullable',
            ],
            'assignedPermissions' => [
                'sometimes',
                'array',
            ],
        ];
    }

    #[\Override] public function toDTO(): object
    {
        $dto = new CreateRoleDTO(
            $this->roleName,
            $this->roleValue,
            $this->roleDescription,
            $this->assignedPermissions,
            $this->roleActive,
        );
        $dto->setUser(Auth::user());

        return $dto;
    }
}
