<?php

declare(strict_types=1);

namespace App\Domain\Oauth\Claim;

use DateTimeImmutable;
use Illuminate\Support\Facades\Cache;
use Lcobucci\JWT\UnencryptedToken;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;

trait CustomClaim
{
    use AccessTokenTrait;

    /**
     * Generate a string representation from the access token
     */
    public function __toString()
    {
        return $this->convertToJWT()->toString();
    }

    /**
     * Generate a JWT from the access token
     *
     * @return UnencryptedToken
     */
    private function convertToJWT(): UnencryptedToken
    {
        $this->initJwtConfiguration();

        return $this->jwtConfiguration
            ->builder()
            ->permittedFor($this->getClient()->getIdentifier())
            ->identifiedBy($this->getIdentifier())
            ->issuedAt(new DateTimeImmutable())
            ->canOnlyBeUsedAfter(new DateTimeImmutable())
            ->expiresAt($this->getExpiryDateTime())
            ->relatedTo((string) $this->getUserIdentifier())
            ->withClaim('scopes', $this->getScopes())
            ->withClaim('device', Cache::pull('jwtDevice'))
            ->withClaim('uuid', Cache::pull('jwtUUID'))
            ->withClaim('roles', Cache::pull('jwtRoles'))
            ->withClaim('permissions', Cache::pull('jwtPermissions'))
            ->withClaim('attributes', Cache::pull('jwtAttributes'))
            ->withClaim('types', Cache::pull('jwtTypes'))
            ->getToken($this->jwtConfiguration->signer(), $this->jwtConfiguration->signingKey());
    }
}
