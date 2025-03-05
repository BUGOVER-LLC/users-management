<?php

declare(strict_types=1);

namespace Infrastructure\Http\Middleware;

use App\Core\Enum\AuthGuard;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if (AuthGuard::webProducer->value === $guard) {
                    return redirect()->route('producerBoard.index');
                }
            }
        }

        return $next($request);
    }
}
