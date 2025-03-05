<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Core\Enum\AuthGuard;
use App\Domain\UMRP\DTO\UpdatePermissionDTO;
use Illuminate\Support\Facades\Auth;
use Override;

/**
 * @property int $permissionId
 * @property string $permissionName
 * @property string $permissionDescription
 * @property bool $permissionActive
 */
class UpdatePermissionRequest extends AbstractRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(AuthGuard::webProducer->value)->check();
    }

    #[Override] public function rules(): array
    {
        return [
            'access' => [
                'array',
            ],
            'access.*' => [
                'nullable',
                'int',
            ],
            'permissionId' => [
                'required',
                'int',
                'exists:Permissions,permissionId',
            ],
            'permissionName' => [
                'required',
                'max:100',
                'min:3',
            ],
            'permissionValue' => [
                'required',
                'max:100',
                'min:3',
            ],
            'permissionDescription' => [
                'sometimes',
                'max:500',
                'min:5',
            ],
            'permissionActive' => [
                'bool',
            ],
        ];
    }

    #[Override] public function toDTO(): object
    {
        return new UpdatePermissionDTO(
            id: $this->permissionId,
            name: $this->permissionName,
            value: $this->permissionValue,
            description: $this->permissionDescription,
            active: $this->permissionActive
        );
    }
}
