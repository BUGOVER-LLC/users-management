<?php

declare(strict_types=1);

namespace App\Domain\Producer\Repository;

use App\Domain\UMAC\Model\Profile;
use Illuminate\Contracts\Container\Container;
use Service\Repository\Repositories\EloquentRepository;

class ProfileRepository extends EloquentRepository
{
    public function __construct(Container $container)
    {
        $this
            ->setContainer($container)
            ->setModel(Profile::class)
            ->setRepositoryId('Profiles')
            ->setCacheDriver('redis');
    }

    /**
     * @param string $uuid
     * @return Profile|null
     */
    public function findCurrentProfileByUuid(string $uuid): ?Profile
    {
        return $this
            ->whereHas(
                rel: 'user',
                callback: fn($query) => $query->where('uuid', '=', $uuid),
            )
            ->orWhereHas(
                relation: 'citizen',
                callback: fn($query) => $query->where('uuid', '=', $uuid),
            )
            ->with(['user', 'citizen'])
            ->findFirst();
    }
}
