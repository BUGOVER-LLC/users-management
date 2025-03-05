<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Core\Enum\AuthGuard;
use App\Domain\UMRP\DTO\CreatePermissionDTO;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Illuminate\Support\Rule\CheckPermissionUniqueInSystemEnv;

class CreatePermissionRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(AuthGuard::webProducer->value)->check();
    }

    #[\Override] public function rules(): array
    {
        return [
            'permissionName' => [
                'required',
                'max:100',
                'min:3',
                new CheckPermissionUniqueInSystemEnv($this->user()->currentSystemId),
            ],
            'permissionValue' => [
                'required',
                'max:100',
                'min:3',
                new CheckPermissionUniqueInSystemEnv($this->user()->currentSystemId),
            ],
            'permissionDescription' => [
                'sometimes',
                'max:500',
                'min:5',
            ],
            'permissionActive' => [
                'required',
                'bool',
            ],
            'permissionId' => [
                'nullable',
            ],
        ];
    }

    #[\Override] public function toDTO(): object
    {
        $dto = new CreatePermissionDTO(
            name: $this->permissionName,
            value: $this->permissionValue,
            description: $this->permissionDescription,
            active: $this->permissionActive
        );
        $dto->setUser(Auth::user());

        return $dto;
    }
}
