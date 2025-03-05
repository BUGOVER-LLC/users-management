<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Repository;

use App\Domain\CUM\Model\Citizen;
use App\Domain\UMAC\Model\Profile;
use App\Domain\UMAC\Model\User;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Service\Repository\Repositories\EloquentRepository;

class ProfileRepository extends EloquentRepository
{
    public function __construct(Container $container)
    {
        $this
            ->setContainer($container)
            ->setModel(Profile::class)
            ->setRepositoryId(Profile::getTableName())
            ->setCacheDriver('redis');
    }

    /**
     * @param $psn
     * @return Profile<User, Citizen>|null
     */
    public function getProfileByPsnWithCitizenUser($psn): ?Profile
    {
        return $this
            ->where('psn', '=', $psn)
            ->with('citizen', 'user')
            ->findFirst();
    }

    public function getProfileByPsnWithCitizen($psn): ?Profile
    {
        return $this
            ->where('psn', '=', $psn)
            ->with('citizen')
            ->findFirst();
    }

    /**
     * @param string|int $document
     * @return Profile|null
     */
    public function findByDocumentValue(string|int $document): ?Profile
    {
        return $this
            ->whereHas('citizen', fn(Builder $qb) => $qb
                ->where('documentValue', '=', $document))
            ->with('citizen')
            ->findFirst();
    }

    /**
     * @param int $citizenId
     * @return Profile<Citizen>|null
     */
    public function findByCitizenId(int $citizenId): ?Profile
    {
        return $this
            ->whereHas('citizen', fn(Builder $qb) => $qb
                ->where('citizenId', '=', $citizenId))
            ->with('citizen')
            ->findFirst();
    }

    /**
     * @param int $psn
     * @return ?Profile
     */
    public function findByPsn(int $psn): ?Profile
    {
        return $this
            ->where('psn', '=', $psn)
            ->findFirst();
    }

    /**
     * @param string $uuid
     * @return Profile|null
     */
    public function findCurrentProfileByUuid(string $uuid): ?Profile
    {
        return $this
            ->with([
                'user' => fn(BelongsTo $qb) => $qb->where('uuid', '=', $uuid),
                'citizen' => fn(BelongsTo $qb) => $qb->where('uuid', '=', $uuid),
            ])
            ->whereHas(
                rel: 'user',
                callback: fn($query) => $query->where('uuid', $uuid),
            )
            ->orWhereHas(
                relation: 'citizen',
                callback: fn($query) => $query->where('uuid', $uuid),
            )
            ->findFirst();
    }

    /**
     * @param string $email
     * @return Profile|null
     */
    public function findCurrentProfileByEmail(string $email): ?Profile
    {
        return $this
            ->whereHas(
                rel: 'user',
                callback: fn($query) => $query->where('email', '=', $email),
            )
            ->orWhereHas(
                relation: 'citizen',
                callback: fn($query) => $query->where('email', '=', $email),
            )
            ->findFirst();
    }

    /**
     * @param string $uuid
     * @return User|Citizen|null
     */
    public function findCurrentAuthByUuid(string $uuid): null|User|Citizen
    {
        $result = $this
            ->with([
                'user' => fn($query) => $query->where('uuid', '=', $uuid),
                'citizen' => fn($query) => $query->where('uuid', '=', $uuid),
            ])
            ->findFirst();

        return $result?->user ?? $result?->citizen ?? null;
    }
}
