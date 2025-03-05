<?php

namespace Infrastructure\Illuminate\Auth;

use App\Domain\UMAC\Repository\ProfileRepository;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Infrastructure\Illuminate\Auth\Contracts\AuthorizedUser as AuthorizedUserContract;

class AuthorizedUser implements AuthorizedUserContract
{
    public function user(): ?AuthenticatableContract
    {
        $uuid = request()->header('AuthorizationId');

        Log::error('Authorized USER: ', [$uuid]);

        if (! $uuid) {
            throw new UnauthorizedException();
        }

        $profile = app(ProfileRepository::class)->findCurrentProfileByUuid($uuid);

        if ($profile->user?->uuid === $uuid) {
            return $profile->user;
        }

        if ($profile->citizen?->uuid === $uuid) {
            return $profile->citizen;
        }

        return null;
    }
}
