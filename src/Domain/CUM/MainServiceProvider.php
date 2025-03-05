<?php

declare(strict_types=1);

namespace App\Domain\CUM;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class MainServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/route.php');

        $this->registerPasswordBroker();
        $this->registerFileDefaultValidation();
    }

    protected function registerPasswordBroker(): void
    {
        Password::defaults(fn () =>
            Password::min(8)
                ->mixedCase()
                ->symbols()
                ->numbers()
                ->uncompromised());
    }

    protected function registerFileDefaultValidation(): void
    {
        File::defaults(
            fn (): File => File::types(['png', 'jpeg', 'jpg'])
                ->max(5 * 1024),
        );
    }
}
