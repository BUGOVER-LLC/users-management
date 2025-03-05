<?php

namespace App\Domain\CUM\Repository;

use App\Domain\CUM\Model\InvitationCitizen;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Collection;
use Service\Repository\Repositories\EloquentRepository;

class InvitationCitizenRepository extends EloquentRepository
{
    public function __construct(Container $container)
    {
        $this
            ->setContainer($container)
            ->setModel(InvitationCitizen::class)
            ->setRepositoryId(InvitationCitizen::getTableName());
    }

    /**
     * @param int $days
     * @return Collection<InvitationCitizen>
     */
    public function getOldestInvitationsByDay(int $days): Collection
    {
        return $this->whereBetween('createdAt', '<=', now()->subDays($days))->findAll();
    }

    /**
     * @param int $citizenId
     * @return InvitationCitizen|null
     */
    public function findByCitizenId(int $citizenId): ?InvitationCitizen
    {
        return $this->where('citizenId', '=', $citizenId)->findFirst();
    }
}
