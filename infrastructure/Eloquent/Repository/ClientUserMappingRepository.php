<?php

declare(strict_types=1);

namespace Infrastructure\Eloquent\Repository;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Infrastructure\Eloquent\Model\ClientUserMapping;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Service\Repository\Exceptions\RepositoryException;
use Service\Repository\Repositories\EloquentRepository;

class ClientUserMappingRepository extends EloquentRepository
{
    public function __construct(Container $container)
    {
        $this
            ->setContainer($container)
            ->setModel(ClientUserMapping::class)
            ->setCacheDriver('redis')
            ->setRepositoryId(ClientUserMapping::getTableName());
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     * @throws ContainerExceptionInterface
     * @throws BindingResolutionException
     * @throws \JsonException
     */
    public function storeMap(int $systemId, int $userId, string $userMap)
    {
        return $this->updateOrCreate(
            ['systemId', '=', $systemId, 'userId', '=', $userId, 'userType', '=', $userMap],
            [
                'systemId' => $systemId,
                'userId' => $userId,
                'userType' => $userMap,
            ]
        );
    }

    /**
     * @param int $systemId
     * @param int $userId
     * @param string $userMap
     * @return bool
     */
    public function hasExistsUserInSystem(int $systemId, int $userId, string $userMap): bool
    {
        return $this
            ->where('systemId', '=', $systemId)
            ->where('userId', '=', $userId)
            ->where('userType', '=', $userMap)
            ->exists();
    }
}
