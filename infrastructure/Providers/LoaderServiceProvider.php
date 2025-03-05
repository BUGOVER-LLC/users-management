<?php

declare(strict_types=1);

namespace Infrastructure\Providers;

use Carbon\Laravel\ServiceProvider;
use Infrastructure\Http\Macro\ErrorMessage;
use Infrastructure\Http\Macro\NotFound;
use Infrastructure\Http\Macro\Unauthenticated;
use Infrastructure\Providers\Traits\AutoLoaderTrait;
use JetBrains\PhpStorm\NoReturn;

class LoaderServiceProvider extends ServiceProvider
{
    use AutoLoaderTrait;

    /**
     * Register any application services.
     *
     * @return void
     */
    #[\Override]
    public function register(): void
    {
        parent::register();

        $this->runLoaderRegister();
    }

    #[\Override]
    public function boot(): void
    {
        parent::boot();

        $this->bindMacros();
        $this->runLoadersBoot();
    }

    /**
     * @return void
     */
    private function bindMacros(): void
    {
        ErrorMessage::bind();
        NotFound::bind();
        Unauthenticated::bind();
    }
}
