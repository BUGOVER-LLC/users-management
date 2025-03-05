<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Http\Controller;

trait DataHeaders
{
    /**
     * @return array[]
     */
    protected function getDataHeadersInvitations(): array
    {
        return [
            'headers' => [
                [
                    'text' => 'Հ/Հ',
                    'value' => 'user.userId',
                    'sortable' => true,
                ],
                [
                    'text' => (string) __('producer.first_name'),
                    'value' => 'profile.psnInfo.firstName',
                    'sortable' => true,
                ],
                [
                    'text' => (string) __('producer.last_name'),
                    'value' => 'profile.psnInfo.lastName',
                    'sortable' => true,
                ],
                [
                    'text' => (string) __('producer.patronymic'),
                    'value' => 'profile.psnInfo.patronymic',
                    'sortable' => true,
                ],
                [
                    'text' => (string) __('producer.psn'),
                    'value' => 'profile.psnInfo.psn',
                    'sortable' => true,
                ],
                [
                    'text' => (string) __('producer.is_active'),
                    'value' => 'user.active',
                    'sortable' => true,
                ],
                [
                    'text' => (string) __('producer.options'),
                    'value' => 'options',
                    'sortable' => false,
                ],
            ],
        ];
    }

    /**
     * @return array[]
     */
    protected function getDataHeadersProfile(): array
    {
        return [
            'headers' => [
                [
                    'text' => 'Հ/Հ',
                    'value' => 'user.userId',
                    'sortable' => true,
                ],
                [
                    'text' => (string) __('producer.first_name'),
                    'value' => 'profile.firstName',
                    'sortable' => true,
                ],
                [
                    'text' => (string) __('producer.last_name'),
                    'value' => 'profile.lastName',
                    'sortable' => true,
                ],
                [
                    'text' => (string) __('producer.patronymic'),
                    'value' => 'profile.patronymic',
                    'sortable' => true,
                ],
                [
                    'text' => (string) __('producer.psn'),
                    'value' => 'profile.psn',
                    'sortable' => true,
                ],
                [
                    'text' => (string) __('producer.is_active'),
                    'value' => 'user.active',
                    'sortable' => true,
                ],
                [
                    'text' => (string) __('producer.options'),
                    'value' => 'edit',
                    'sortable' => false,
                ],
            ],
        ];
    }
}
