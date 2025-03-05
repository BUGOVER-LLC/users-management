<?php

declare(strict_types=1);

namespace Infrastructure\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            if (Str::contains(url()->current(), 'producer/')) {
                return route('authProducer.index');
            }
        }

        return $request->expectsJson() ? null : route('login');
    }
}
