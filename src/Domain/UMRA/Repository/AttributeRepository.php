<?php

declare(strict_types=1);

namespace App\Domain\UMRA\Repository;

use App\Domain\UMRA\DTO\StoreAttributeDTO;
use App\Domain\UMRA\Model\Attribute;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Service\Repository\Exceptions\RepositoryException;
use Service\Repository\Repositories\EloquentRepository;

class AttributeRepository extends EloquentRepository
{
    public function __construct(Container $container)
    {
        $this
            ->setContainer($container)
            ->setModel(Attribute::class)
            ->setRepositoryId('Attributes')
            ->setCacheDriver('redis');
    }

    /**
     * @param int $systemId
     * @return Collection<Attribute>
     */
    public function findAllBySystemId(int $systemId): Collection
    {
        return $this
            ->where('systemId', '=', $systemId)
            ->with([
                'resource',
            ])
            ->findAll();
    }

    /**
     * @param int $userId
     * @return Attribute|null
     */
    public function findByUserSystemId(int $userId): ?Attribute
    {
        return $this
            ->whereHas('users', fn(Builder $qb) => $qb->where('Users.userId', '=', $userId))
            ->with('resource')
            ->findFirst();
    }

    /**
     * @param StoreAttributeDTO $dto
     * @return object|bool|null
     * @throws BindingResolutionException
     * @throws \JsonException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     */
    public function storeAttribute(StoreAttributeDTO $dto): object|bool|null
    {
        return $this->store($dto->attributeId, [
            'attributeName' => $dto->attributeName,
            'attributeValue' => $dto->attributeValue,
            'attributeDescription' => $dto->attributeDescription,
            'resourceId' => $dto->resourceId,
            'systemId' => $dto->getUser()->currentSystemId,
        ]);
    }

    /**
     * @param int $systemId
     * @param string $attributeName
     * @return bool
     */
    public function existsAttributeNameInSystem(int $systemId, string $attributeName): bool
    {
        return $this
            ->where('systemId', '=', $systemId)
            ->where('attributeName', '=', $attributeName)
            ->exists();
    }

    /**
     * @param int $systemId
     * @param string $attributeValue
     * @return bool
     */
    public function existsAttributeValueInSystem(int $systemId, string $attributeValue): bool
    {
        return $this
            ->where('systemId', '=', $systemId)
            ->where('attributeValue', '=', $attributeValue)
            ->exists();
    }


    /**
     * @param string $resource
     * @return Collection<Attribute>
     */
    public function findAllByResoure(string $resource): Collection
    {
        return $this
            ->whereHas('resource', fn(Builder $qb) => $qb->where('resourceValue', '=', $resource))
            ->findAll();
    }

    /**
     * @param string $attributeValue
     * @return Attribute|null
     */
    public function findByValue(string $attributeValue): ?Attribute
    {
        return $this->where('attributeValue', '=', $attributeValue)->findFirst();
    }
}
