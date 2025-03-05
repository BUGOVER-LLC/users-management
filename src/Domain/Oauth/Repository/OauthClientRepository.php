<?php

declare(strict_types=1);

namespace App\Domain\Oauth\Repository;

use App\Domain\Oauth\Model\Client;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Log;
use Service\Repository\Repositories\EloquentRepository;

class OauthClientRepository extends EloquentRepository
{
    public function __construct(Container $container)
    {
        $this
            ->setContainer($container)
            ->setModel(Client::class)
            ->setRepositoryId('OauthClients');
    }

    /**
     * @return ?Client
     */
    public function getPasswordClient($provider): ?Client
    {
        return $this
            ->where('password_client', '=', true)
            ->where('provider', '=', $provider)
            ->findFirst();
    }

    public function getPersonalAccessClient(): ?Client
    {
        return $this
            ->where('personal_access_client', '=', true)
            ->where('provider', '=')
            ->findFirst();
    }

    /**
     * @param string $secret
     * @param string $domain
     * @return ?Client
     */
    public function findBySecretAndDomain(string $secret, string $domain): ?Client
    {
        Log::error($domain);

        return $this
            ->where('secret', '=', $secret)
//            ->whereHas('system', fn(Builder $qb) => $qb->where('systemDomain', '=', $domain))
            ->findFirst();
    }

    /**
     * @param string $secret
     * @return ?Client
     */
    public function findBySecret(string $secret): ?Client
    {
        return $this
            ->where('secret', '=', $secret)
            ->findFirst();
    }
}
