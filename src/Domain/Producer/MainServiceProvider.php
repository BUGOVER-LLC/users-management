<?php

declare(strict_types=1);

namespace App\Domain\Producer;

use Illuminate\Support\ServiceProvider as BaseProvider;

class MainServiceProvider extends BaseProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/route.php');
    }
}
