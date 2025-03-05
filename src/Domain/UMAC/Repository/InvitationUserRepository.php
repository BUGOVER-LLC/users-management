<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Repository;

use App\Domain\UMAC\Model\InvitationUser;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Collection;
use Service\Repository\Repositories\EloquentRepository;

class InvitationUserRepository extends EloquentRepository
{
    public function __construct(Container $container)
    {
        $this
            ->setContainer($container)
            ->setModel(InvitationUser::class)
            ->setRepositoryId(InvitationUser::getTableName())
            ->setCacheDriver('redis');
    }

    /**
     * @param int $days
     * @return Collection<InvitationUser>
     */
    public function getOldestInvitationsByDay(int $days): Collection
    {
        return $this->where('createdAt', '<=', now()->subDays($days))->findAll();
    }

    /**
     * @param int $userId
     * @return ?InvitationUser
     */
    public function findByUserId(int $userId): ?InvitationUser
    {
        return $this->where('userId', '=', $userId)->findFirst();
    }

    /**
     * @param string $token
     * @return ?InvitationUser
     */
    public function getPendingActiveInviteWithUser(string $token): ?InvitationUser
    {
        return $this
            ->where('acceptedAt', '=')
            ->where('inviteToken', '=', $token)
            ->with('user')
            ->findFirst();
    }
}
