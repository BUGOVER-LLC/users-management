<?php

declare(strict_types=1);

namespace Infrastructure\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Infrastructure\Illuminate\Auth\AuthorizedUser;
use Infrastructure\Illuminate\Auth\Contracts\AuthorizedUser as AuthorizedUserContract;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }

    #[\Override]
    public function register(): void
    {
        $this->app->bind(
            abstract: AuthorizedUserContract::class,
            concrete: fn(Application $app) => new AuthorizedUser(),
        );
    }
}
