<?php

declare(strict_types=1);

namespace App\Domain\Oauth\Repository;

use App\Domain\Oauth\Model\Client;
use App\Domain\Oauth\Model\Token;
use DateTime;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Cache;
use Infrastructure\Eloquent\Model\ClientDevice;
use Infrastructure\Eloquent\Repository\ClientDeviceRepository;
use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\FormatsScopesForStorage;
use Laravel\Passport\Events\AccessTokenCreated;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Override;
use Service\Repository\Exceptions\RepositoryException;
use Service\Repository\Repositories\EloquentRepository;

class OauthAccessTokenRepository extends EloquentRepository implements AccessTokenRepositoryInterface
{
    use FormatsScopesForStorage;

    public function __construct(
        Container $container,
        private readonly AccessTokenRepository $accessTokenRepository,
        private readonly Dispatcher $events,
        private readonly ClientDeviceRepository $clientDeviceRepository
    )
    {
        $this
            ->setContainer($container)
            ->setModel(Token::class)
            ->setRepositoryId(Token::class);
    }

    #[Override] public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        return $this->accessTokenRepository->getNewToken($clientEntity, $scopes, $userIdentifier);
    }

    /**
     * Persists a new access token to permanent storage.
     *
     * @param AccessTokenEntityInterface $accessTokenEntity
     *
     * @throws BindingResolutionException
     * @throws RepositoryException
     */
    #[Override] public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
        $_deviceName = Cache::get('jwtDevice');
        $device = $_deviceName ? $this->clientDeviceRepository
            ->where('device', '=', $_deviceName)
            ->where('clientId', '=', $accessTokenEntity->getUserIdentifier())
            ->firstLatest('loggedAt') : null;

        $this->create([
            'id' => $accessTokenEntity->getIdentifier(),
            'user_id' => $accessTokenEntity->uuid ?? $accessTokenEntity->getUserIdentifier(),
            'client_id' => $accessTokenEntity->getClient()->getIdentifier(),
            'deviceId' => $device?->clientDeviceId,
            'scopes' => $this->scopesToArray($accessTokenEntity->getScopes()),
            'revoked' => false,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'expires_at' => $accessTokenEntity->getExpiryDateTime(),
        ]);

        $this->events->dispatch(
            event: new AccessTokenCreated(
                tokenId: $accessTokenEntity->getIdentifier(),
                userId: $accessTokenEntity->getUserIdentifier(),
                clientId: $accessTokenEntity->getClient()->getIdentifier()
            )
        );
    }

    /**
     * Revoke an access token.
     *
     * @param string $tokenId
     */
    #[Override] public function revokeAccessToken($tokenId): void
    {
        $this->accessTokenRepository->revokeAccessToken($tokenId);
    }

    /**
     * {@inheritDoc}
     */
    #[Override] public function isAccessTokenRevoked($tokenId): bool
    {
        return $this->accessTokenRepository->isAccessTokenRevoked($tokenId);
    }

    /**
     * @param string $id
     * @return Token<Client>|null
     */
    public function findByIdWithClient(string $id): ?Token
    {
        return $this
            ->where('id', '=', $id)
            ->with(['client'])
            ->findFirst();
    }

    /**
     * @param string $id
     * @return Token<ClientDevice>|null
     */
    public function findByIdWithDevice(string $id): ?Token
    {
        return $this
            ->where('id', '=', $id)
            ->with(['device'])
            ->findFirst();
    }
}
