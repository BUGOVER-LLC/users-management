<?php

declare(strict_types=1);

namespace App\Domain\Micro;

use App\Core\Trait\RegisterListeners;
use Illuminate\Support\ServiceProvider;

class MainServiceProvider extends ServiceProvider
{
    use RegisterListeners;

    public $listeners = [
        'citizen.created' => \App\Domain\Micro\Listeners\CitizenCreatedListener::class,
        'user.created' => \App\Domain\Micro\Listeners\UserCreatedListener::class,
        'user.token.revoke' => \App\Domain\Micro\Listeners\UserTokenRevokeListener::class,
    ];

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/route.php');
        $this->registerListeners();
    }
}
