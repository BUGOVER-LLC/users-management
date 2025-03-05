<?php

declare(strict_types=1);

namespace App\Domain\Oauth;

use App\Domain\Oauth\Claim\CustomAccessToken;
use App\Domain\Oauth\Model\AuthCode;
use App\Domain\Oauth\Model\Client;
use App\Domain\Oauth\Model\PersonalClient;
use App\Domain\Oauth\Model\RefreshToken;
use App\Domain\Oauth\Model\Token;
use Illuminate\Contracts\Container\BindingResolutionException;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider as BaseServiceProviderAlias;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\ResourceServer;

class MainServiceProvider extends BaseServiceProviderAlias
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/route.php');

        // Keys path
        Passport::loadKeysFrom(base_path('oauth'));
        Passport::keyPath(base_path('oauth'));

        // Grants
        Passport::enablePasswordGrant();
        Passport::enableImplicitGrant();

        // Custom JWT Generator
        Passport::useAccessTokenEntity(CustomAccessToken::class);

        // Scopes
        Passport::tokensCan(config('scope.allowed'));
        Passport::setDefaultScope(config('scope.defaults'));

        // Models
        Passport::useTokenModel(Token::class);
        Passport::useClientModel(Client::class);
        Passport::useAuthCodeModel(AuthCode::class);
        Passport::useRefreshTokenModel(RefreshToken::class);
        Passport::usePersonalAccessClientModel(PersonalClient::class);

        // Expires
        Passport::tokensExpireIn(now()->addDays(30));
        Passport::refreshTokensExpireIn(now()->addDays(90));
        Passport::personalAccessTokensExpireIn(now()->addDays(30));
    }


    /**
     * Make the authorization service instance.
     *
     * @return AuthorizationServer
     * @throws BindingResolutionException
     */
    public function makeAuthorizationServer()
    {
        return new AuthorizationServer(
            clientRepository: $this->app->make(\Laravel\Passport\Bridge\ClientRepository::class),
            accessTokenRepository: $this->app->make(\App\Domain\Oauth\Repository\OauthAccessTokenRepository::class),
            scopeRepository: $this->app->make(\Laravel\Passport\Bridge\ScopeRepository::class),
            privateKey: $this->makeCryptKey('private'),
            encryptionKey: app('encrypter')->getKey(),
            responseType: Passport::$authorizationServerResponseType
        );
    }

    /**
     * Register the resource server.
     *
     * @return void
     */
    protected function registerResourceServer()
    {
        $this->app->singleton(ResourceServer::class, function ($container) {
            return new ResourceServer(
                accessTokenRepository: $container->make(\App\Domain\Oauth\Repository\OauthAccessTokenRepository::class),
                publicKey: $this->makeCryptKey('public')
            );
        });
    }
}
