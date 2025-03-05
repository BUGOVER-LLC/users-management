<?php

declare(strict_types=1);

namespace App\Domain\UMRA\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\UMRA\DTO\CreateRoomDTO;
use App\Domain\UMRA\Model\Attribute;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\ObjectShape;

class RoomCreateRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    #[\Override] public function rules(): array
    {
        return [
            'attributeId' => [
                'sometimes',
                'integer',
                'exists:' . Attribute::getTableName() . ',' . Attribute::getPrimaryName(),
            ],
            'roomName' => [
                'required',
                'string',
            ],
            'roomValue' => [
                'required',
                'string',
            ],
            'roomDescription' => [
                'sometimes',
                'nullable',
                'string',
            ],
        ];
    }

    #[
        ObjectShape([
            'attributeId' => 'int',
            'roomName' => 'string',
            'roomValue' => 'string',
            'roomDescription' => ['string', 'null'],
        ])
    ]
    #[\Override] public function toDTO(): object
    {
        return new CreateRoomDTO(
            attributeId: $this->attributeId,
            systemId: Auth::user()->currentSystemId,
            roomName: $this->roomName,
            roomValue: $this->roomValue,
            roomDescription: $this->roomDescription,
        );
    }
}
