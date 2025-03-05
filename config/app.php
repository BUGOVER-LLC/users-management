<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */
    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */
    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | API SERVICE Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */
    'service_api_url' => env('SERVICE_URL'),

    /*
    |--------------------------------------------------------------------------
    | CONTENT SECURITY CONFIG
    |--------------------------------------------------------------------------
    |
    | This values determines for an enabled and exclude CSP.
    |
    */
    'csp' => [
        'enable' => env('CONTENT_SECURITY_POLICY_ENABLE', false),
        'exclude' => explode(',', trim(env('CONTENT_SECURITY_POLICY_EXCLUDE') ?? '')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */
    'service_ekeng_url' => env('SERVICE_EKENG_URL', 'https://ekeng.asd.am/api/avv/search'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */
    'debug' => (bool)env('APP_DEBUG', false),

    /**
     * In development state STRICT_MODE enable
     */
    'strict' => env('STRICT_MODE', false),

    /**
     * In development state STRICT_MODE enable please, and level 3,2,1
     */
    'strict_level' => env('STRICT_LEVEL', 3),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */
    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */
    'timezone' => env('APP_TIMEZONE', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */
    'locale' => env('APP_LOCAL', 'hy'),

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */
    'fallback_locale' => env('APP_FALLBACK_LOCAL', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */
    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */
    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */
    'maintenance' => [
        'driver' => 'file',
        // 'store' => 'redis',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */
    'providers' => Illuminate\Support\ServiceProvider::defaultProviders()->merge([
        /*
         * Application Service Providers...
         */
        \Infrastructure\Providers\AppServiceProvider::class,
        \Infrastructure\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        \Infrastructure\Providers\EventServiceProvider::class,
        \Infrastructure\Providers\RouteServiceProvider::class,
        \Infrastructure\Providers\ComposeServiceProvider::class,
        \Infrastructure\Providers\LoaderServiceProvider::class,
        \Infrastructure\Providers\MacroServiceProvider::class,

        /**
         * Domain Core Provider
         */
        \App\Core\FileSystem\FileSystemServiceProvider::class,

        /**
         * Domain service providers...
         */
        \App\Domain\Oauth\MainServiceProvider::class,
        \App\Domain\Producer\MainServiceProvider::class,
        \App\Domain\UMRP\MainServiceProvider::class,
        \App\Domain\UMAC\MainServiceProvider::class,
        \App\Domain\UMAA\MainServiceProvider::class,
        \App\Domain\UMRA\MainServiceProvider::class,
        \App\Domain\CUM\MainServiceProvider::class,
        \App\Domain\Micro\MainServiceProvider::class,
        \App\Domain\System\MainServiceProvider::class,
        \App\Domain\Attribute\MainServiceProvider::class,
    ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */
    'aliases' => Illuminate\Support\Facades\Facade::defaultAliases()->merge([// 'Example' => App\Facades\Example::class,
    ])->toArray(),
];
