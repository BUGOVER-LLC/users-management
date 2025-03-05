<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Request\SyncDataParams;

trait RoleParam
{
    private array $roleParams = [
        'data.*.roleValue' => [
            'required',
            'string',
        ],
        'data.*.roleName' => [
            'required',
            'string',
        ],
        'data.*.roleDescription' => [
            'nullable',
            'sometimes',
            'string',
        ],
        'data.*.roleActive' => [
            'nullable',
            'sometimes',
            'bool',
        ],
        'data.*.hasSubordinates' => [
            'nullable',
            'sometimes',
            'bool',
        ],
        'data.*.permissionValues' => [
            'sometimes',
            'array',
        ],
    ];
}
