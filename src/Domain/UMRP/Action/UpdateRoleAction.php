<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Action;

use App\Core\Abstract\AbstractAction;
use App\Domain\UMRP\Action\Role\RoleSyncAccessor;
use App\Domain\UMRP\DTO\UpdateRoleDTO;
use App\Domain\UMRP\Repository\RolePermissionRepository;
use App\Domain\UMRP\Repository\RoleRepository;
use App\Domain\UMRP\Service\RoleService;
use Exception;
use Infrastructure\Exceptions\ServerErrorException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @method transactionalRun(UpdateRoleDTO $dto)
 */
final class UpdateRoleAction extends AbstractAction
{
    use RoleSyncAccessor;

    public function __construct(
        private readonly RoleRepository $roleRepository,
        private readonly RolePermissionRepository $rolePermissionRepository,
        private readonly RoleService $roleService
    )
    {
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ServerErrorException
     * @throws ContainerExceptionInterface
     */
    public function run(UpdateRoleDTO $dto)
    {
        $permissionAccess = $this->mapPermissionAccess($dto);

        try {
            $this->roleRepository->update($dto->roleId, [
                'roleName' => $dto->name,
                'roleValue' => $dto->value,
                'roleDescription' => $dto->description,
                'roleActive' => $dto->active,
                'hasSubordinates' => $dto->hasSubordinates,
                'permissions' => array_keys($permissionAccess),
            ], true);

            $rolePermissions = $this->rolePermissionRepository->findAllByRoleId($dto->roleId);
            $this->syncAccessors($rolePermissions, $permissionAccess);
        } catch (Exception) {
            throw new ServerErrorException();
        }

        return $this->roleService->getRoleByValue($dto->value);
    }
}
