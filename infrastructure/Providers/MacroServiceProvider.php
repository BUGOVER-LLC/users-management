<?php

declare(strict_types=1);

namespace Infrastructure\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerCollectionMacros();
    }

    protected function registerCollectionMacros(): void
    {
        Collection::macro('transformKeys', fn ($callback) =>
            $this->mapWithKeys(fn ($value, $key) => [
                $callback($key) => \is_array($value) ? collect($value)->transformKeys($callback) : $value,
            ])
        );
    }
}
