<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Action\Role;

use App\Domain\UMRP\Model\RolePermission;
use Illuminate\Database\Eloquent\Collection;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Service\Repository\Exceptions\RepositoryException;

trait RoleSyncAccessor
{
    /**
     * @param Collection $rolePermissions
     * @param array $permissionAccess
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     * @throws \JsonException
     */
    private function syncAccessors(Collection $rolePermissions, array $permissionAccess): void
    {
        /* @var RolePermission $rolePermission */
        foreach ($rolePermissions as $rolePermission) {
            foreach ($permissionAccess as $permissionId => $access) {
                if ($rolePermission->permissionId === $permissionId) {
                    $this->rolePermissionRepository
                        ->update(
                            $rolePermission->rolePermissionId,
                            ['access' => $access]
                        );
                }
            }
        }
    }

    /**
     * @param $dto
     * @return array
     */
    private function mapPermissionAccess($dto): array
    {
        $permissionAccess = [];
        foreach ($dto->assignedPermissions as $permission) {
            $permissionAccess[$permission['permissionId']] = $permission['access'];
        }

        return $permissionAccess;
    }
}
