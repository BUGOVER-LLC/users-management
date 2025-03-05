<?php

declare(strict_types=1);

namespace App\Domain\UMRA\Service;

use App\Domain\UMRA\DTO\CreateRoomDTO;
use App\Domain\UMRA\DTO\StoreRoomDTO;
use App\Domain\UMRA\Model\Room;
use App\Domain\UMRA\Repository\RoomRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use Infrastructure\Exceptions\ServerErrorException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Service\Repository\Exceptions\RepositoryException;
use Symfony\Component\Config\Definition\Exception\Exception;

class QueryService
{
    public function __construct(private readonly RoomRepository $roomRepository)
    {
    }

    /**
     * @param Collection $storeRoomDTO
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws BindingResolutionException
     * @throws \JsonException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     */
    public function storeRooms(int $attributeId, Collection $storeRoomDTO): \Illuminate\Database\Eloquent\Collection
    {
        $roomsCount = $this->roomRepository
            ->where('attributeId', '=', $attributeId)
            ->count();

        if ($roomsCount === $storeRoomDTO->count()) {
            /* @var StoreRoomDTO $room */
            foreach ($storeRoomDTO as $room) {
                $this->roomRepository->store($room->roomId, [
                    'attributeId' => $room->attributeId,
                    'roomName' => $room->roomName,
                    'roomValue' => $room->roomValue,
                    'roomDescription' => $room->roomDescription,
                ]);
            }
        }

        if (0 === $storeRoomDTO->count()) {
            $this->roomRepository
                ->where('attributeId', '=', $attributeId)
                ->deletes();
        }

        if (1 === $roomsCount || $roomsCount > $storeRoomDTO->count()) {
            /* @var StoreRoomDTO $room */
            foreach ($storeRoomDTO as $room) {
                $this->roomRepository->delete($room->roomId);
            }
        }

        return $this->roomRepository
            ->where('attributeId', '=', $storeRoomDTO->pluck('attributeId')->first())
            ->findAll();
    }

    /**
     * @param CreateRoomDTO $createRoomDTO
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws BindingResolutionException
     * @throws RepositoryException
     * @throws ServerErrorException
     */
    public function createRoom(CreateRoomDTO $createRoomDTO): \Illuminate\Database\Eloquent\Collection
    {
        try {
            $this->roomRepository->create($createRoomDTO->toArray());
        } catch (Exception $exception) {
            logging($exception);

            throw new ServerErrorException();
        }

        return $this->roomRepository
            ->where('attributeId', '=', $createRoomDTO->attributeId)
            ->findAll();
    }
}
