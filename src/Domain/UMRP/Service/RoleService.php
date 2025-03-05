<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Service;

use App\Domain\UMRP\DTO\CreatePermissionDTO;
use App\Domain\UMRP\DTO\UpdatePermissionDTO;
use App\Domain\UMRP\Model\Permission;
use App\Domain\UMRP\Model\Role;
use App\Domain\UMRP\Repository\PermissionRepository;
use App\Domain\UMRP\Repository\RoleRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Exceptions\ServerErrorException;
use Infrastructure\Http\DTO\DefaultPaginateDTO;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class RoleService
{
    public function __construct(
        private readonly RoleRepository $roleRepository,
        private readonly PermissionRepository $permissionRepository,
    )
    {
    }

    /**
     * @return LengthAwarePaginator<Permission>
     */
    public function getAllPermissions(DefaultPaginateDTO $dto): LengthAwarePaginator
    {
        return $this->permissionRepository
            ->where('systemId', '=', Auth::user()->currentSystemId)
            ->paginate($dto->perPage, ['*'], 'page', $dto->page);
    }

    /**
     * @param int $roleId
     * @return Role|null
     */
    public function findRoleWithPermissions(int $roleId): ?Role
    {
        return $this->roleRepository->forgetCache()->with('permissions')->find($roleId);
    }

    /**
     * @param int $roleId
     * @return Collection<Permission>
     */
    public function findPermissionsExceptRole(int $roleId): Collection
    {
        return $this->permissionRepository->forgetCache()->whereDoesntHave(
            'roles',
            fn(Builder $qb) => $qb->where('Roles.roleId', '=', $roleId)
        )->findAll();
    }

    /**
     * @param string $value
     * @return ?Role
     */
    public function getRoleByValue(string $value): ?Role
    {
        return $this->roleRepository->where('roleValue', '=', $value)->findFirst();
    }

    /**
     * @param CreatePermissionDTO $dto
     * @return Permission|null
     */
    public function createPermission(CreatePermissionDTO $dto): ?Permission
    {
        try {
            $this->permissionRepository->create([
                'systemId' => $dto->getUser()->currentSystemId,
                'permissionName' => $dto->name,
                'permissionValue' => $dto->value,
                'permissionDescription' => $dto->description,
                'permissionActive' => $dto->active,
            ]);
        } catch (Exception $exception) {
            logging($exception);

            throw new RuntimeException(trans('messages.server_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->getPermissionByName($dto->name);
    }

    /**
     * @param string $name
     * @return ?Permission
     */
    public function getPermissionByName(string $name): ?Permission
    {
        return $this->permissionRepository->where('permissionName', '=', $name)->findFirst();
    }

    /**
     * @param UpdatePermissionDTO $dto
     * @return Permission|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ServerErrorException
     */
    public function updatePermission(UpdatePermissionDTO $dto): ?Permission
    {
        try {
            $this->permissionRepository->update($dto->id, [
                'permissionName' => $dto->name,
                'permissionValue' => $dto->value,
                'permissionDescription' => $dto->description,
                'permissionActive' => $dto->active,
            ]);
        } catch (Exception $exception) {
            logging($exception);

            throw new ServerErrorException();
        }

        return $this->getPermissionByName($dto->name);
    }

    /**
     * @param DefaultPaginateDTO $dto
     * @return LengthAwarePaginator
     */
    public function getRolePager(DefaultPaginateDTO $dto): LengthAwarePaginator
    {
        return $this->roleRepository
            ->when(
                value: $dto->search,
                callback: fn(Builder $qb) => $qb->with('permissions')->where('roleName', 'LIKE', "%$dto->search%")
                    ->orWhere(
                        column: 'roleDescription',
                        operator: 'LIKE',
                        value: "%$dto->search%"
                    )
            )
            ->where('systemId', '=', $dto->getUser()->currentSystemId)
            ->paginate($dto->perPage, ['*'], 'page', $dto->page);
    }

    /**
     * @param int $roleId
     * @return true
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ServerErrorException
     */
    public function deleteRole(int $roleId): bool
    {
        try {
            $this->roleRepository->delete($roleId, ['permissions' => []]);
        } catch (Exception $exception) {
            logging($exception);
            throw new ServerErrorException();
        }

        return true;
    }

    /**
     * @return Collection
     */
    public function findAvailablePermissions(): Collection
    {
        return $this->permissionRepository
            ->where('systemId', '=', Auth::user()->currentSystemId)
            ->where('permissionActive', '=', true)
            ->findAll();
    }

    /**
     * @return Collection
     */
    public function findAvailableRoles(): Collection
    {
        return $this->roleRepository
            ->where('systemId', '=', Auth::user()->currentSystemId)
            ->where('roleActive', '=', true)
            ->findAll();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws ServerErrorException
     * @throws NotFoundExceptionInterface
     */
    public function permissionDelete(int $permissionId): true
    {
        try {
            $this->permissionRepository->delete($permissionId, ['roles' => []]);
        } catch (Exception $exception) {
            logging($exception);
            throw new ServerErrorException();
        }

        return true;
    }
}
