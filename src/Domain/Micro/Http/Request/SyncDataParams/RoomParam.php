<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Request\SyncDataParams;

trait RoomParam
{
    private array $roomParams = [
        'data.*.roomValue' => [
            'required',
            'string',
        ],
        'data.*.roomName' => [
            'required',
            'string',
        ],
        'data.*.roomDescription' => [
            'nullable',
            'sometimes',
            'string',
        ],
        'data.*.attributeValue' => [
            'nullable',
            'sometimes',
            'string',
        ],
    ];
}
