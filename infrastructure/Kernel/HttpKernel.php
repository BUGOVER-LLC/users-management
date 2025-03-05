<?php

declare(strict_types=1);

namespace Infrastructure\Kernel;

use Illuminate\Foundation\Http\Kernel as BaseHttpKernel;

class HttpKernel extends BaseHttpKernel
{
    /**
     * The application's global Http middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \Infrastructure\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \Infrastructure\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Infrastructure\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \Infrastructure\Http\Middleware\ForceJsonResponse::class,
        \Infrastructure\Http\Middleware\Security\HSTS::class,
        \Infrastructure\Http\Middleware\Security\HTTPS::class,
        \Infrastructure\Http\Middleware\Security\CSP::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \Infrastructure\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Infrastructure\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Infrastructure\Http\Middleware\CheckProducerHasEnvironment::class,
            \Infrastructure\Http\Interceptor\AppResponseInterceptor::class,
        ],

        'api' => [
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Infrastructure\Http\Middleware\ForceJsonResponse::class,
            \Infrastructure\Http\Interceptor\ApiResponseInterceptor::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \Infrastructure\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \Infrastructure\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \Infrastructure\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        // App Core middlewares
        'set_auth_payload' => \App\Domain\Producer\Http\Middleware\SetAuthData::class,
        // Oauth Server middlewares
        'scopes' => \Laravel\Passport\Http\Middleware\CheckScopes::class,
        'scope' => \Laravel\Passport\Http\Middleware\CheckForAnyScope::class,
        'client' => \Laravel\Passport\Http\Middleware\CheckClientCredentials::class,
        // Check exists authorized user in database and return true or false accordingly
        'authorize' => \Infrastructure\Http\Middleware\ValidateAuthorizedUser::class,
    ];
}
