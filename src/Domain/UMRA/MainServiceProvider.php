<?php

declare(strict_types=1);

namespace App\Domain\UMRA;

use Illuminate\Support\ServiceProvider;

class MainServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/route.php');
    }
}
