<?php

declare(strict_types=1);

namespace Infrastructure\Providers\Loaders;

use Composer\ClassMapGenerator\ClassMapGenerator;
use Exception;
use Illuminate\Support\Str;

trait ObserverLoader
{
    /**
     * @param array $models
     * @return int[]|string[]
     */
    private function observerRegister(array $models): array
    {
        $eloquentObserverPath = base_path('infrastructure/Observer/Observe');

        try {
            $domainObserve = array_keys(ClassMapGenerator::createMap(base_path('src/Domain/*/Observer')));
            $eloquentObserve = is_dir($eloquentObserverPath) ? array_keys(
                ClassMapGenerator::createMap($eloquentObserverPath)
            ) : [];
        } catch (Exception) {
            return [];
        }

        $observers = array_merge($domainObserve, $eloquentObserve);

        foreach ($models as $model) {
            foreach ($observers as $observe) {
                if ((Str::afterLast($model, '\\') . 'Observe') === (Str::afterLast($observe, '\\'))) {
                    $model::observe($observe);
                }
            }
        }

        return $observers;
    }
}
