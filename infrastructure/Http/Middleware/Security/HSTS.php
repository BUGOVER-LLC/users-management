<?php

declare(strict_types=1);

namespace Infrastructure\Http\Middleware\Security;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HSTS
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // @TODO UNDER TESTING
        if (app()->isLocal()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=2592000; includeSubdomains');
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
            $response->headers->set('Referrer-Policy', 'no-referrer');
            $response->headers->set('Cross-Origin-Embedder-Policy', 'unsafe-none');
            $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
            $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');
            $response->headers->set('X-XSS-Protection', '1; mode=block');
        }

        return $response;
    }
}
