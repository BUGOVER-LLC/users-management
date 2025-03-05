<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Request\SyncDataParams;

trait PermissionParam
{
    private array $permissionParams = [
        'data.*.permissionValue' => [
            'required',
            'string',
        ],
        'data.*.permissionName' => [
            'required',
            'string',
        ],
        'data.*.permissionDescription' => [
            'nullable',
            'sometimes',
            'string',
        ],
    ];
}
