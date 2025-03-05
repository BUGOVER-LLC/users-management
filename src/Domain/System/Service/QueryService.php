<?php

declare(strict_types=1);

namespace App\Domain\System\Service;

use App\Core\Enum\OauthClientType;
use App\Domain\Oauth\Repository\OauthClientRepository;
use App\Domain\Oauth\Repository\PersonalAccessTokenRepository;
use App\Domain\Producer\Repository\ProducerRepository;
use App\Domain\System\Http\DTO\StoreEnvironmentDTO;
use App\Domain\System\Http\DTO\StoreSystemDTO;
use App\Domain\System\Model\System;
use App\Domain\System\Repository\SystemRepository;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Infrastructure\Exceptions\ServerErrorException;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Service\Repository\Exceptions\RepositoryException;

/**
 * @method System transactionRun(StoreEnvironmentDTO $dto)
 */
readonly class QueryService
{
    public function __construct(
        private SystemRepository $systemRepository,
        private OauthClientRepository $oauthClientRepository,
        protected ClientRepository $clientRepository,
        private PersonalAccessTokenRepository $personalAccessTokenRepository,
        private ProducerRepository $producerRepository
    )
    {
    }

    /**
     * @param StoreEnvironmentDTO $dto
     * @return System
     */
    public function saveOrSetEnvironment(StoreEnvironmentDTO $dto): System
    {
        $systemId = DB::transaction(function () use ($dto) {
            if ($dto->systemId && $dto->systemName) {
                /* @var System $currentSystem */
                $currentSystem = $this->systemRepository->update($dto->systemId, [
                    'systemName' => $dto->systemName,
                    'systemLogo' => $dto->systemLogo,
                    'systemDomain' => $dto->systemDomain,
                ]);
            } elseif (!$dto->systemId) {
                $currentSystem = $this->systemRepository->create([
                    'systemName' => $dto->systemName,
                    'producerId' => $dto->getId(),
                    'systemLogo' => $dto->systemLogo,
                    'systemDomain' => $dto->systemDomain,
                ]);

                Passport::tokensExpireIn(now()->addDays(360));
                $this->clientRepository->create(
                    $currentSystem?->systemId,
                    $currentSystem?->systemName,
                    '',
                );
            }

            $systemId = $currentSystem->systemId ?? $dto->systemId;
            $this->producerRepository->update($dto->getId(), ['currentSystemId' => $systemId]);

            return $systemId;
        });

        return $this->systemRepository->with('publicClient')->find($systemId);
    }

    /**
     * @param int $userId
     * @return Collection
     */
    public function findAllClientByUserId(int $userId): Collection
    {
        return $this->oauthClientRepository
            ->where('user_id', '=', $userId)
            ->where('personal_access_client', '=', false)
            ->findAll();
    }

    /**
     * @param int $systemId
     * @return System
     */
    public function findSystemById(int $systemId): System
    {
        return $this->systemRepository->find($systemId);
    }

    /**
     * @param StoreSystemDTO $dto
     * @return Client
     * @throws BindingResolutionException
     * @throws RepositoryException
     */
    public function createClient(StoreSystemDTO $dto): Client
    {
        /* @var \App\Domain\Oauth\Model\Client $client */
        $client = $this->clientRepository->create(
            userId: $dto->getUser()->currentSystemId,
            name: $dto->clientName,
            redirect: $dto->clientRedirect,
            provider: $dto->clientProvider,
            personalAccess: OauthClientType::personal->value === $dto->clientType,
            password: OauthClientType::password->value === $dto->clientType,
            confidential: !(OauthClientType::public->value === $dto->clientType),
        );

        if (OauthClientType::personal->value === $dto->clientType) {
            $this->personalAccessTokenRepository->create(['client_id' => $client->id]);
        }

        return $client;
    }

    public function revokeAuthClient(int $clientId): void
    {
        $client = $this->clientRepository->find($clientId);

        if (!$client) {
            throw new ServerErrorException();
        }

        $this->clientRepository->delete($client);
    }

    public function deleteAuthClient(int $clientId): void
    {
        try {
            $this->oauthClientRepository->delete($clientId);
        } catch (Exception $exception) {
            logging($exception);

            throw new ServerErrorException();
        }
    }
}
