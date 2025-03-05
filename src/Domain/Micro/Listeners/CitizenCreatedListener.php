<?php

declare(strict_types=1);

namespace App\Domain\Micro\Listeners;

use App\Domain\CUM\Model\Citizen;
use App\Domain\Oauth\Repository\OauthAccessTokenRepository;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Infrastructure\Eloquent\Repository\ClientUserMappingRepository;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Service\Repository\Exceptions\RepositoryException;

class CitizenCreatedListener implements ShouldQueue
{
    public function __construct(
        private readonly OauthAccessTokenRepository $oauthAccessTokenRepository,
        private readonly ClientUserMappingRepository $userMappingRepository
    )
    {
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     * @throws ContainerExceptionInterface
     * @throws BindingResolutionException
     * @throws \JsonException
     */
    public function handle(string $accessToken, int $citizenId): void
    {
        $id = $this->getScopes($accessToken);
        $token = $this->oauthAccessTokenRepository->findByIdWithClient($id);
        $this->userMappingRepository->storeMap($token->client->user_id, $citizenId, Citizen::map());
    }

    /**
     * @param string $bearerToken
     * @return string|null
     */
    private function getScopes(string $bearerToken): ?string
    {
        try {
            $scopes = (new Parser(new JoseEncoder()))
                ->parse($bearerToken)
                ->claims()
                ->get('jti');
        } catch (Exception $exception) {
            logging($exception);
        }

        return $scopes ?? null;
    }
}
