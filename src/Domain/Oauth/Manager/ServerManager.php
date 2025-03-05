<?php

declare(strict_types=1);

namespace App\Domain\Oauth\Manager;

use App\Domain\CUM\Action\CreateCitizenPersonalAccessTokenAction;
use App\Domain\Oauth\Action\CreatePasswordGrantClientAction;
use App\Domain\Oauth\Model\Client;
use App\Domain\Oauth\Repository\OauthClientRepository;

class ServerManager
{
    /**
     * @param string $uuid
     * @param string $email
     * @param string $password
     * @param string $provider
     * @param bool $switchedAccount
     * @param array|string $scope
     *
     * @return array{bearer_token: string, refresh_token: string, beraer_type: string}
     */
    public static function createBearerByEmailPWDWithScope(
        string $uuid,
        string $email,
        string $password,
        string $provider,
        bool $switchedAccount = false,
        array|string $scope = ['*'],
    ): array
    {
        if ($switchedAccount) {
            return app(CreateCitizenPersonalAccessTokenAction::class)
                ->run($uuid);
        }

        return app(CreatePasswordGrantClientAction::class)
            ->run($uuid, $email, $password, $provider, $scope);
    }

    /**
     * @param string $provider
     * @return Client|null
     */
    public static function getPasswordGuardClient(string $provider = 'users'): ?Client
    {
        return app(OauthClientRepository::class)->getPasswordClient($provider);
    }
}
