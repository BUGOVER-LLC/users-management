<?php

declare(strict_types=1);

namespace Infrastructure\Illuminate\Auth\Contracts;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

interface AuthorizedUser
{
    public function user(): ?AuthenticatableContract;
}
