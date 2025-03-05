<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Action\Role;

use App\Core\Abstract\AbstractAction;
use App\Domain\UMRP\DTO\CreateRoleDTO;
use App\Domain\UMRP\Model\Role;
use App\Domain\UMRP\Repository\RolePermissionRepository;
use App\Domain\UMRP\Repository\RoleRepository;
use App\Domain\UMRP\Service\RoleService;
use Exception;
use Infrastructure\Exceptions\ServerErrorException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @method transactionalRun(CreateRoleDTO $dto)
 */
final class CreateRoleAction extends AbstractAction
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
     * @throws ServerErrorException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function run(CreateRoleDTO $dto)
    {
        $permissionAccess = $this->mapPermissionAccess($dto);

        try {
            /* @var ?Role $role */
            $role = $this->roleRepository->create([
                'systemId' => $dto->getUser()->currentSystemId,
                'roleName' => $dto->name,
                'roleValue' => $dto->value,
                'roleDescription' => $dto->description,
                'roleActive' => $dto->active,
                'hasSubordinates' => $dto->hasSubordinates,
                'permissions' => array_keys($permissionAccess),
            ], true);

            if ($role?->roleId) {
                $rolePermissions = $this->rolePermissionRepository->findAllByRoleId($role->roleId);
                $this->syncAccessors($rolePermissions, $permissionAccess);
            }
        } catch (Exception) {
            throw new ServerErrorException();
        }

        return $this->roleService->getRoleByValue($dto->value);
    }
}
