<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Repository;

use App\Domain\UMRP\Model\Permission;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Service\Repository\Repositories\EloquentRepository;

class PermissionRepository extends EloquentRepository
{
    public function __construct(Container $container)
    {
        $this
            ->setContainer($container)
            ->setModel(Permission::class)
            ->setRepositoryId(Permission::getTableName());
    }

    /**
     * @param int $userId
     * @return Collection<Permission>
     */
    public function getPermissionByUserId(int $userId): Collection
    {
        return $this->whereHas('users', function (Builder $qb) use ($userId) {
            $qb->where('userId', '=', $userId);
        })->findAll();
    }

    /**
     * @param int $roleId
     * @return Collection<Permission>
     */
    public function findPermissionsByRoleId(int $roleId): Collection
    {
        return $this->whereHas(
            'roles',
            fn(\Illuminate\Database\Eloquent\Builder $qb) => $qb->where('Roles.roleId', '=', $roleId)
        )->findAll();
    }

    /**
     * @param int $systemId
     * @return Collection<Permission>
     */
    public function findAllBySystemRoles(int $systemId): Collection
    {
        return $this->where('systemId', '=', $systemId)->findAll();
    }

    /**
     * @param int $systemId
     * @param string $permissionName
     * @return bool
     */
    public function existsPermissionNameInSystem(int $systemId, string $permissionName): bool
    {
        return $this
            ->where('systemId', '=', $systemId)
            ->where('permissionName', '=', $permissionName)
            ->exists();
    }

    /**
     * @param int $systemId
     * @param string $roleName
     * @return bool
     */
    public function existsPermissionValueInSystem(int $systemId, string $permissionValue): bool
    {
        return $this
            ->where('systemId', '=', $systemId)
            ->where('permissionValue', '=', $permissionValue)
            ->exists();
    }
}
