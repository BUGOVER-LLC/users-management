<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Manager;

use App\Domain\UMRP\Repository\PermissionRepository;
use Illuminate\Database\Eloquent\Collection;

class ServerManager
{
    /**
     * @param int $userId
     * @return Collection
     */
    public static function getUserPermissionsById(int $userId): Collection
    {
        return app(PermissionRepository::class)->getPermissionByUserId($userId);
    }
}
