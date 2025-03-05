<?php

declare(strict_types=1);

namespace App\Domain\UMAA;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class MainServiceProvider extends BaseServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/route.php');
    }
}
