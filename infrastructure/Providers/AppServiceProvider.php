<?php

declare(strict_types=1);

namespace Infrastructure\Providers;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Http\Interceptor\AddHttp2ServerPush;
use Infrastructure\Illuminate\Database\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[\Override]
    public function register(): void
    {
        JsonResource::withoutWrapping();
        JsonResource::wrap('_payload');

        $this->app->bind(
            'db.schema',
            fn() => Schema::customizedSchemaBuilder()
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Kernel $kernel): void
    {
        $this->strictModeAdoption();

        if (app()->environment('local')) {
            $this->initProdMiddleware($kernel);
        }
    }

    /**
     * Is determinate strict mode level for eloquent
     *
     * @return void
     */
    protected function strictModeAdoption(): void
    {
        if (app()->environment('local')) {
            return;
        }

        $strict = (bool) config('app.strict');
        $level = (int) config('app.strict_level');

        if ($strict) {
            if (1 === $level) {
                Model::shouldBeStrict();
            } elseif (2 === $level) {
                Model::preventLazyLoading();
                Model::preventAccessingMissingAttributes();
                Model::preventSilentlyDiscardingAttributes(false);
            } elseif (3 === $level) {
                Model::preventSilentlyDiscardingAttributes(false);
            }
        }
    }

    private function initProdMiddleware(Kernel $kernel)
    {
        $kernel->prependMiddleware(
            AddHttp2ServerPush::class, //@TODO FIX FOR PRODUCTION
        );
    }
}
