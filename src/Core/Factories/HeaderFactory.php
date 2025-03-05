<?php

declare(strict_types=1);

namespace App\Core\Factories;

final class HeaderFactory
{
    public static function defaults(): array
    {
        return [
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ];
    }
}
