<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Request\SyncDataParams;

trait ResourceData
{
    private array $resourceParams = [
        'data.*.resourceValue' => [
            'required',
            'string',
        ],
        'data.*.resourceName' => [
            'required',
            'string',
        ],
        'data.*.resourceDescription' => [
            'nullable',
            'sometimes',
            'string',
        ],
    ];
}
