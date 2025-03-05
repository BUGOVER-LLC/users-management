<?php

declare(strict_types=1);

namespace App\Domain\UMRP;

use Illuminate\Support\ServiceProvider;

class MainServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/route.php');
    }
}
