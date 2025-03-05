<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Manager;

use App\Domain\UMRP\Manager\ServerManager as UMRCServer;
use App\Domain\UMRP\Model\Permission;
use Illuminate\Database\Eloquent\Collection;

class ClientManager
{
    /**
     * @param int $userId
     * @return Collection<Permission>
     */
    public function getPermissionsByUserId(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return UMRCServer::getUserPermissionsById($userId);
    }
}
