<?php

declare(strict_types=1);

namespace Infrastructure\Http\Middleware\Security;

use Closure;
use Illuminate\Http\Request;

/**
 * Class HttpsProtocol
 *
 * @package Src\Http\Middleware
 */
class HTTPS
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        if (!$request->secure() && !app()->isLocal()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
