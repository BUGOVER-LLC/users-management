<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Http\Controller;

trait dataHeader
{
    /**
     * @return array[]
     */
    protected function getRoleHeaders(): array
    {
        return [
            'headers' => [
                [
                    'text' => 'Հ/Հ',
                    'value' => 'roleId',
                    'sortable' => true,
                ],
                [
                    'text' => __('producer.role_name'),
                    'value' => 'roleName',
                    'sortable' => false,
                ],
                [
                    'text' => __('producer.role_value'),
                    'value' => 'roleValue',
                    'sortable' => false,
                ],
                [
                    'text' => __('producer.role_description'),
                    'value' => 'roleDescription',
                    'sortable' => false,
                ],
                [
                    'text' => __('producer.is_active'),
                    'value' => 'roleActive',
                    'sortable' => false,
                ],
                [
                    'text' => __('roles.created'),
                    'value' => 'createdAt',
                    'sortable' => false,
                ],
                [
                    'text' => __('producer.options'),
                    'value' => 'syncPermission',
                    'sortable' => false,
                ],
            ],
        ];
    }
}
