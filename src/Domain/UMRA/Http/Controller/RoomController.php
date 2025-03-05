<?php

declare(strict_types=1);

namespace App\Domain\UMRA\Http\Controller;

use App\Domain\UMRA\Http\Request\RoomCreateRequest;
use App\Domain\UMRA\Http\Request\RoomStoreRequest;
use App\Domain\UMRA\Repository\RoomRepository;
use App\Domain\UMRA\Service\QueryService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Infrastructure\Http\Controllers\Controller;
use Infrastructure\Http\Resource\RoomResource;

final class RoomController extends Controller
{
    public function __construct(private readonly RoomRepository $roomRepository, private readonly QueryService $service)
    {
    }

    public function getRooms(int $attributeId): AnonymousResourceCollection
    {
        $rooms = $this->roomRepository->findAllRoomByAttributeId($attributeId);

        return RoomResource::collection($rooms);
    }

    public function setRooms(RoomStoreRequest $request, int $attributeId): AnonymousResourceCollection
    {
        $dto = $request->toDTO();
        $result = $this->service->storeRooms($attributeId, $dto);

        return RoomResource::collection($result);
    }

    public function createRoom(RoomCreateRequest $request): AnonymousResourceCollection
    {
        $dto = $request->toDTO();
        $result = $this->service->createRoom($dto);

        return RoomResource::collection($result);
    }
}
