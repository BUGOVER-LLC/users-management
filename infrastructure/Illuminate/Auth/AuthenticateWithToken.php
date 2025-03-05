<?php

declare(strict_types=1);

namespace Infrastructure\Illuminate\Auth;

use App\Domain\CUM\Model\Citizen;
use App\Domain\Oauth\Repository\OauthAccessTokenRepository;
use App\Domain\Oauth\Repository\OauthClientRepository;
use App\Domain\UMAC\Model\User;
use Illuminate\Validation\UnauthorizedException;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

class AuthenticateWithToken
{
    protected OauthAccessTokenRepository $oauthAccessTokenRepository;

    protected OauthClientRepository $oauthClientRepository;

    protected Signer $signer;

    protected Configuration $configuration;

    protected string $jti;

    protected User|Citizen $provider;

    public function __construct()
    {
        $this->oauthAccessTokenRepository = app(OauthAccessTokenRepository::class);
        $this->oauthClientRepository = app(OauthClientRepository::class);
        $this->signer = new Sha256();
        $this->configuration = $this->buildConfig();
    }

    protected function buildConfig(): Configuration
    {
        $config = Configuration::forAsymmetricSigner(
            signer: $this->signer,
            signingKey: $this->getSigningKey(),
            verificationKey: $this->getVerificationKey(),
        );

        $config->withValidationConstraints(
            new SignedWith(
                signer: $this->signer,
                key: $this->getVerificationKey(),
            )
        );

        return $config;
    }

    protected function getSigningKey(): Key
    {
        return $this->getKey(
            contents: file_get_contents(storage_path('private/oauth/oauth-private.key')),
        );
    }

    /**
     * Get the signing key instance.
     */
    protected function getKey(string $contents, string $passphrase = ''): Key
    {
        return InMemory::plainText($contents, $passphrase);
    }

    protected function getVerificationKey(): Key
    {
        return $this->getKey(
            contents: file_get_contents(storage_path('private/oauth/oauth-public.key')),
        );
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        $bearerToken = request()->bearerToken();

        if (!$bearerToken) {
            throw new UnauthorizedException();
        }

        $token = (new Parser(new JoseEncoder()))->parse($bearerToken);
        $this->jti = $token->claims()->get('jti');

        if (!$this->validate($token)) {
            throw new \RuntimeException('Invalid token');
        }

        return $this->user();
    }

    public function validate(Plain $token): bool
    {
        $constraints = $this->configuration->validationConstraints();

        return $this->configuration
            ->validator()
            ->validate($token, ...$constraints);
    }

    protected function user()
    {
        $accessToken = $this->oauthAccessTokenRepository->findByIdWithClient($this->jti);
        $provider = $accessToken?->client->provider;

        if (!$provider) {
            throw new \RuntimeException('Unable to retrieve OAuth provider');
        }

        $model = config("auth.providers.{$provider}.model");

        return $model::findOrFail($accessToken?->user_id);
    }
}
