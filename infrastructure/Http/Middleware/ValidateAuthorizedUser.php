<?php

declare(strict_types=1);

namespace Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Infrastructure\Illuminate\Auth\Contracts\AuthorizedUser;
use Symfony\Component\HttpFoundation\Response;

class ValidateAuthorizedUser
{
    public function __construct(
        protected readonly AuthorizedUser $authorizedUser,
    )
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->authorizedUser->user()) {
            throw new UnauthorizedException();
        }

        return $next($request);
    }
}
