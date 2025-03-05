<?php

declare(strict_types=1);

namespace App\Domain\UMRA\Http\Controller;

trait AttributesHeader
{
    /**
     * @return array[]
     */
    protected function getDataHeadersAttributes(): array
    {
        return [
            'headers' => [
                [
                    'text' => 'Հ/Հ',
                    'value' => 'attribute.attributeId',
                    'sortable' => true,
                ],
            ],
        ];
    }
}
