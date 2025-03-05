<?php

declare(strict_types=1);

namespace App\Domain\UMRA\Repository;

use App\Domain\UMRA\DTO\StoreResourceDTO;
use App\Domain\UMRA\Model\Resource;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Collection;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Service\Repository\Exceptions\RepositoryException;
use Service\Repository\Repositories\EloquentRepository;

class ResourceRepository extends EloquentRepository
{
    public function __construct(Container $container)
    {
        $this
            ->setContainer($container)
            ->setModel(Resource::class)
            ->setRepositoryId(Resource::getTableName())
            ->setCacheDriver('redis');
    }

    /**
     * @param int $systemId
     * @return Collection<Resource>
     */
    public function findAllBySystemId(int $systemId): Collection
    {
        return $this->where('systemId', '=', $systemId)->findAll();
    }

    /**
     * @param string $value
     * @return Resource|null
     */
    public function findByValue(string $value): ?Resource
    {
        return $this->where('resourceValue', '=', $value)->findFirst();
    }

    /**
     * @param StoreResourceDTO $dto
     * @return object|bool|null
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     * @throws \JsonException
     */
    public function storeResource(StoreResourceDTO $dto): object|bool|null
    {
        return $this->store($dto->resourceId, [
            'systemId' => $dto->getUser()->currentSystemId,
            'resourceName' => $dto->resourceName,
            'resourceValue' => $dto->resourceValue,
            'resourceDescription' => $dto->resourceDescription,
        ]);
    }
}
