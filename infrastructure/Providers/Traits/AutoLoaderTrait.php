<?php

declare(strict_types=1);

namespace Infrastructure\Providers\Traits;

use Infrastructure\Providers\Loaders\ModelMapLoader;
use Infrastructure\Providers\Loaders\ObserverLoader;
use Infrastructure\Providers\Loaders\PolicyLoader;
use Infrastructure\Providers\Loaders\SupportLoader;
use JetBrains\PhpStorm\NoReturn;

trait AutoLoaderTrait
{
    use SupportLoader;
    use ObserverLoader;
    use ModelMapLoader;
    use PolicyLoader;

    public function runLoaderRegister(): void
    {
    }

    /**
     * To be used from the `boot` function of the main service provider.
     */
    #[NoReturn] public function runLoadersBoot(): void
    {
        $this->filtersRegister();
        $models = $this->loadModelMaps();
        $this->observerRegister($models);
        $this->policyRegister($models);
    }
}
