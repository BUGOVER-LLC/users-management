<?php

declare(strict_types=1);

namespace App\Domain\UMRA\Repository;

use App\Domain\UMRA\Model\Room;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Collection;
use Service\Repository\Repositories\EloquentRepository;

class RoomRepository extends EloquentRepository
{
    public function __construct(Container $container)
    {
        $this
            ->setContainer($container)
            ->setModel(Room::class)
            ->setRepositoryId(Room::getTableName())
            ->setCacheDriver('redis');
    }

    /**
     * @param int $attributeId
     * @return Collection
     */
    public function findAllRoomByAttributeId(int $attributeId): Collection
    {
        return $this
            ->where('attributeId', '=', $attributeId)
            ->findAll();
    }
}
