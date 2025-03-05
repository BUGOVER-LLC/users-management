<?php

declare(strict_types=1);

namespace App\Domain\UMAC;

use Illuminate\Support\ServiceProvider;

class MainServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/route.php');
    }
}
