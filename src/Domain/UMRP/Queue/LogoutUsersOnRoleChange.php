<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Queue;

use App\Domain\UMRP\Action\Role\RoleUpdateSyncAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogoutUsersOnRoleChange implements ShouldQueueAfterCommit
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly int $roleId)
    {
    }

    /**
     * @param RoleUpdateSyncAction $action
     * @return void
     */
    public function handle(RoleUpdateSyncAction $action): void
    {
        $action->transactionalRun($this->roleId);
    }
}
