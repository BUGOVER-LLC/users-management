<?php

declare(strict_types=1);

namespace Infrastructure\Providers\Loaders;

use Composer\ClassMapGenerator\ClassMapGenerator;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

trait PolicyLoader
{
    public function policyRegister($models)
    {
        $infrastructurePoliciesPath = base_path('infrastructure/Eloquent/Policy');

        if (!is_dir(base_path('src/Domain/*/Policy'))) {
            return;
        }

        try {
            $domainPolicies = array_keys(ClassMapGenerator::createMap(base_path('src/Domain/*/Policy')));
            $infrastructurePolicies = is_dir($infrastructurePoliciesPath) ? array_keys(
                ClassMapGenerator::createMap(base_path('src/Domain/*/Policy'))
            ) : [];
        } catch (Exception $exception) {
            logging($exception);
        }

        $policies = array_merge($infrastructurePolicies ?? [], $domainPolicies ?? []);

        foreach ($models as $model) {
            foreach ($policies as $policy) {
                if ((Str::afterLast($model, '\\').'Policy') === (Str::afterLast($policy, '\\'))) {
                    Gate::policy($model, $policy);
                }
            }
        }
    }
}
