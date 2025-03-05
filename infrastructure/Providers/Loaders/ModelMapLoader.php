<?php

declare(strict_types=1);

namespace Infrastructure\Providers\Loaders;

use Composer\ClassMapGenerator\ClassMapGenerator;
use Exception;
use Illuminate\Database\Eloquent\Relations\Relation;
use Infrastructure\Illuminate\Model\Contract\EntityContract;

trait ModelMapLoader
{
    /**
     * @return string[]
     */
    private function loadModelMaps(): array
    {
        try {
            $domain_models = array_keys(ClassMapGenerator::createMap(base_path('src/Domain/*/Model')));
            $eloquent_models = array_keys(ClassMapGenerator::createMap(base_path('infrastructure/Eloquent/Model')));
        } catch (Exception) {
            return [];
        }

        $models = array_merge($domain_models, $eloquent_models);

        $result = [];
        foreach ($models as $model) {
            $instance = (new $model());
            if ($instance instanceof EntityContract && property_exists($model, 'map') && $instance->getMap()) {
                $result[$instance->getMap()] = $model;
            }
        }

        Relation::morphMap($result);

        return $result;
    }
}
