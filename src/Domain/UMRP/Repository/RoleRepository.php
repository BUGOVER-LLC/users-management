<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Repository;

use App\Domain\UMRP\Model\Role;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Collection;
use Service\Repository\Repositories\EloquentRepository;

class RoleRepository extends EloquentRepository
{
    public function __construct(Container $container)
    {
        $this
            ->setContainer($container)
            ->setModel(Role::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('Roles');
    }

    /**
     * @param int $roleId
     * @return ?Role
     */
    public function findWithPermissions(int $roleId): ?Role
    {
        return $this
            ->with('permissions')
            ->find($roleId);
    }

    /**
     * @param int $systemId
     * @return Collection<Role>
     */
    public function findAllBySystemRoles(int $systemId): Collection
    {
        return $this->where('systemId', '=', $systemId)->findAll();
    }

    /**
     * @param int $systemId
     * @param string $roleName
     * @return bool
     */
    public function existsRoleNameInSystem(int $systemId, string $roleName): bool
    {
        return $this
            ->where('systemId', '=', $systemId)
            ->where('roleName', '=', $roleName)
            ->exists();
    }

    /**
     * @param int $systemId
     * @param string $roleName
     * @return bool
     */
    public function existsRoleValueInSystem(int $systemId, string $roleValue): bool
    {
        return $this
            ->where('systemId', '=', $systemId)
            ->where('roleValue', '=', $roleValue)
            ->exists();
    }
}
