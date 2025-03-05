<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Request\SyncDataParams;

trait AttributeData
{
    private array $attributeParams = [
        'data.*.attributeValue' => [
            'required',
            'string',
        ],
        'data.*.attributeName' => [
            'required',
            'string',
        ],
        'data.*.attributeDescription' => [
            'nullable',
            'sometimes',
            'string',
        ],
        'data.*.resourceValue' => [
            'required',
            'string',
        ],
    ];
}
