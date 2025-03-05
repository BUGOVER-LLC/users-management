<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\Micro\Http\DTO\SyncDataDTO;
use App\Domain\Micro\Http\Request\SyncDataParams\AttributeData;
use App\Domain\Micro\Http\Request\SyncDataParams\PermissionParam;
use App\Domain\Micro\Http\Request\SyncDataParams\ResourceData;
use App\Domain\Micro\Http\Request\SyncDataParams\RoleParam;
use App\Domain\Micro\Http\Request\SyncDataParams\RoomParam;
use Override;
use RuntimeException;

/**
 * @property bool $force
 * @property string $type
 * @property array $data
 */
class SyncDataRequest extends AbstractRequest
{
    use AttributeData;
    use ResourceData;
    use PermissionParam;
    use RoleParam;
    use RoomParam;

    public const TYPE_ATTRIBUTE = 'attributes';
    public const TYPE_RESOURCE = 'resources';
    public const TYPE_ROLE = 'roles';
    public const TYPE_PERMISSION = 'permissions';
    public const TYPE_ROOMS = 'rooms';

    #[Override] public function rules(): array
    {
        $rules = [
            'data' => [
                'array',
                'required',
            ],
            'type' => [
                'string',
            ],
            'force' => [
                'bool',
            ],
        ];

        return match ($this->type) {
            self::TYPE_ATTRIBUTE => array_merge($rules, $this->attributeParams),
            self::TYPE_RESOURCE => array_merge($rules, $this->resourceParams),
            self::TYPE_PERMISSION => array_merge($rules, $this->permissionParams),
            self::TYPE_ROLE => array_merge($rules, $this->roleParams),
            self::TYPE_ROOMS => array_merge($rules, $this->roomParams),
            default => throw new RuntimeException('Invalid type', 422),
        };
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'type' => $this->type,
            'force' => (bool) $this->force,
        ]);
    }

    #[Override] public function toDTO(): SyncDataDTO
    {
        return new SyncDataDTO(
            type: $this->type,
            force: $this->force,
            params: $this->data
        );
    }
}
