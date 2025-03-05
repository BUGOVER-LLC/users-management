<?php

declare(strict_types=1);

namespace Infrastructure\Http\Middleware\Security;

use Closure;
use Illuminate\Http\Request;

class CSP
{
    private const array ALLOWED = [];

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (config('app.csp.enable') && !$request->is(config('app.csp.exclude'))) {
            // @TODO write this section CSP config
        }

        return $response;
    }
}
