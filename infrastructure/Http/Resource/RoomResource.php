<?php

declare(strict_types=1);

namespace Infrastructure\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Domain\UMRA\Model\Room;
use Illuminate\Http\Request;
use Infrastructure\Http\Schemas\RoomSchema;

/**
 * @property-read Room $resource
 */
class RoomResource extends AbstractResource
{
    public function toSchema(Request $request): AbstractSchema
    {
        return new RoomSchema(
            roomId: $this->resource->roomId,
            attributeId: $this->resource->attributeId,
            roomName: $this->resource->roomName,
            roomValue: $this->resource->roomValue,
            roomDescription: $this->resource->roomDescription,
            roomActive: $this->resource->roomActive ?? true,
        );
    }
}
