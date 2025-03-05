<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Repository;

use App\Domain\UMRP\Model\RolePermission;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Collection;
use Service\Repository\Repositories\EloquentRepository;

class RolePermissionRepository extends EloquentRepository
{
    public function __construct(Container $container)
    {
        $this
            ->setContainer($container)
            ->setModel(RolePermission::class)
            ->setRepositoryId(RolePermission::getTableName());
    }

    /**
     * @param int $roleId
     * @return Collection<RolePermission>
     */
    public function findAllByRoleId(int $roleId): Collection
    {
        return $this
            ->where('roleId', '=', $roleId)
            ->findAll();
    }
}
