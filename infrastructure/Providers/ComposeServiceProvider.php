<?php

declare(strict_types=1);

namespace Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class ComposeServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register()
    {
        $this->registerBladeExtensions();
    }

    private function registerBladeExtensions(): void
    {
        $this->app->afterResolving(
            'blade.compiler',
            fn(BladeCompiler $compiler) => $compiler->directive(
                'sqlDuplicate',
                fn($arguments) => "<?php require resource_path('views/dev/duplicate_query_marker.blade.php') ?>"
            )
        );
    }
}
