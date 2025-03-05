<?php

declare(strict_types=1);

namespace App\Core\FileSystem;

use App\Core\Contracts\FileSystemContract;
use Illuminate\Support\ServiceProvider;

/**
 * FileSystem service provider.
 */
final class FileSystemServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        parent::register();

        $this->app->bind(FileSystemContract::class, FileSystem::class);
    }
}
