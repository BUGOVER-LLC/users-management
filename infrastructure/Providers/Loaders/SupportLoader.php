<?php

declare(strict_types=1);

namespace Infrastructure\Providers\Loaders;

use Composer\ClassMapGenerator\ClassMapGenerator;

trait SupportLoader
{
    public function filtersRegister(): void
    {
        if (!is_dir(base_path('infrastructure/Illuminate/Support/Filters'))) {
            return;
        }

        $loadFilters = array_keys(ClassMapGenerator::createMap(base_path('infrastructure/Illuminate/Support/Filters')));

        foreach ($loadFilters as $filter) {
            new $filter($this->app['validator']);
        }
    }
}
