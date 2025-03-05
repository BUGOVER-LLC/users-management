<?php

declare(strict_types=1);

namespace App\Domain\Attribute;

use Illuminate\Support\ServiceProvider as BaseProvider;

class MainServiceProvider extends BaseProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/route.php');
    }
}
