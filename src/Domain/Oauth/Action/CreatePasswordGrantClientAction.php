<?php

declare(strict_types=1);

namespace App\Domain\Oauth\Action;

use App\Core\Abstract\AbstractAction;
use App\Core\Enum\AuthGuard;
use App\Core\Enum\AuthProvider;
use App\Core\Utils\MachineId;
use App\Domain\CUM\Model\Citizen;
use App\Domain\CUM\Repository\CitizenRepository;
use App\Domain\Oauth\Repository\OauthClientRepository;
use App\Domain\UMAC\Model\User;
use App\Domain\UMAC\Repository\UserRepository;
use App\Domain\UMRA\Repository\AttributeRepository;
use App\Domain\UMRP\Repository\RoleRepository;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Infrastructure\Eloquent\Repository\ClientDeviceRepository;
use Infrastructure\Exceptions\OauthServerException;
use Infrastructure\Exceptions\ServerErrorException;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Service\Repository\Exceptions\RepositoryException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method transactionalRun(string $uuid, string $email, string $password, string $provider, array|string $scopes)
 */
final class CreatePasswordGrantClientAction extends AbstractAction
{
    /**
     * @param Client $client
     * @param OauthClientRepository $clientRepository
     * @param UserRepository $userRepository
     * @param RoleRepository $roleRepository
     * @param AttributeRepository $attributeRepository
     * @param ClientDeviceRepository $clientDeviceRepository
     * @param CitizenRepository $citizenRepository
     */
    public function __construct(
        private readonly Client $client,
        private readonly OauthClientRepository $clientRepository,
        private readonly UserRepository $userRepository,
        private readonly RoleRepository $roleRepository,
        private readonly AttributeRepository $attributeRepository,
        private readonly ClientDeviceRepository $clientDeviceRepository,
        private readonly CitizenRepository $citizenRepository
    )
    {
    }

    /**
     * @param string $uuid
     * @param string $email
     * @param string $password
     * @param string $provider
     * @param array|string $scopes
     *
     * @return mixed|null
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws GuzzleException
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     * @throws OauthServerException
     * @throws RepositoryException
     * @throws ServerErrorException
     */
    public function run(
        string $uuid,
        string $email,
        string $password,
        string $provider,
        array|string $scopes,
    ): null|array
    {
        /* @var \App\Domain\Oauth\Model\Client $client */
        $client = $this->clientRepository->getPasswordClient($provider);

        if (!$client) {
            throw new RuntimeException('Invalid oauth client');
        }

        $this->setDeviceRoles($uuid, $provider);
        $formData = $this->getBearerByPwd($email, $password, $client, $provider, $scopes);

        $transformedFormData = collect($formData)
            ->transformKeys(fn(string $key) => Str::camel($key))
            ->put('uuid', $uuid)
            ->toArray();

        $this->sendForLogin($transformedFormData);

        return $transformedFormData;
    }

    /**
     * @param string $uuid
     * @param string $provider
     * @return void
     * @throws JsonException
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     */
    private function setDeviceRoles(string $uuid, string $provider): void
    {
        $device = MachineId::instance();

        /* @var User|Citizen $auth */
        $auth = $this->userRepository->where('uuid', '=', $uuid)->findFirst()
            ?? $this->citizenRepository->where('uuid', '=', $uuid)->findFirst();

        if (!$auth) {
            throw new RuntimeException('Undefined User');
        }

        $this->clientDeviceRepository->persistDevice(
            $auth->getKey(),
            $auth->getMap(),
            $device->getDeviceName(),
        );

        Cache::putMany([
            'jwtDevice' => $device->getDeviceName(),
        ], now()->addMinute());

        if (AuthProvider::users->value === $provider) {
            $role = $this->roleRepository->findWithPermissions($auth->roleId);
            $attribute = $this->attributeRepository->findByUserSystemId($auth->userId);

            if (!$attribute && $auth->parentId) {
                $attribute = $this->userRepository->findParentAttribute($auth->parentId);
                $uuid = $this->userRepository->find($auth->parentId)?->uuid ?? $uuid;
            }

            Cache::putMany([
                'jwtUUID' => $uuid,
                'jwtRoles' => [$role?->roleValue],
                'jwtPermissions' => $role?->permissions->pluck('permissionValue')->toArray(),
                'jwtAttributes' => [$attribute?->attributeValue],
                'jwtTypes' => [$attribute?->resource?->resourceValue],
            ], now()->addMinute());
        }
    }

    /**
     * @param array $formData
     *
     * @return void
     * @throws GuzzleException
     * @throws ServerErrorException
     */
    private function sendForLogin(array $formData): void
    {
        try {
            $response = $this->client->post(config('app.service_api_url') . '/micro/login', [
                'form_params' => $formData,
                'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest'],
            ]);

            if (Response::HTTP_OK !== $response->getStatusCode()) {
                return;
            }
        } catch (Exception $exception) {
            logging($exception);

            throw new ServerErrorException($exception->getMessage());
        }
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     * @throws OauthServerException
     */
    private function getBearerByPwd(
        string $email,
        string $password,
        \App\Domain\Oauth\Model\Client $client,
        string $provider,
        array $scopes = ['*']
    ): array
    {
        $response = $this->client->post(
            config('app.url') . (Str::endsWith(config('app.url'), '/') ? 'oauth/token' : '/oauth/token'),
            [
                'form_params' => [
                    'username' => $email,
                    'password' => $password,
                    'client_id' => $client->id,
                    'client_secret' => $client->secret,
                    'guard' => AuthGuard::apiUsers->value,
                    'grant_type' => 'password',
                    'provider' => $provider,
                    'scope' => $scopes,
                ],
            ]
        );

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new OauthServerException();
        }

        return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }
}
