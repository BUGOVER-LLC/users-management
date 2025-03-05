<?php

declare(strict_types=1);

namespace App\Domain\UMRA\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\UMRA\DTO\StoreRoomDTO;
use App\Domain\UMRA\Model\Attribute;
use App\Domain\UMRA\Model\Room;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\ObjectShape;

class RoomStoreRequest extends AbstractRequest
{
    public function authorize()
    {
        return Auth::check();
    }

    #[\Override] public function rules(): array
    {
        return [
            '*.roomId' => [
                'required',
                'integer',
                'exists:' . Room::getTableName() . ',' . Room::getPrimaryName(),
            ],
            '*.attributeId' => [
                'sometimes',
                'integer',
                'exists:' . Attribute::getTableName() . ',' . Attribute::getPrimaryName(),
            ],
            '*.roomName' => [
                'required',
                'string',
            ],
            '*.roomValue' => [
                'required',
                'string',
            ],
            '*.roomDescription' => [
                'sometimes',
                'nullable',
                'string',
            ],
            '*.roomActive' => [
                'sometimes',
                'bool',
            ],
        ];
    }

    #[
        ObjectShape([
            'roomId' => 'int',
            'attributeId' => 'int',
            'roomName' => 'string',
            'roomValue' => 'string',
            'roomDescription' => ['string', 'null'],
            'roomActive' => 'boolean',
        ])
    ]
    #[\Override] public function toDTO(): object
    {
        $result = collect();

        foreach ($this->request->all() as $item) {
            $dto = new StoreRoomDTO(
                roomId: $item['roomId'],
                attributeId: $item['attributeId'],
                roomName: $item['roomName'],
                roomValue: $item['roomValue'],
                roomDescription: $item['roomDescription']
            );
            $result->push($dto);
        }

        return $result;
    }
}
