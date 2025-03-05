<?php

declare(strict_types=1);

namespace App\Domain\Oauth\Action;

use App\Core\Abstract\AbstractAction;
use App\Domain\Oauth\Repository\OauthClientRepository;
use App\Domain\UMRA\Repository\AttributeRepository;
use App\Domain\UMRP\Repository\PermissionRepository;
use App\Domain\UMRP\Repository\RoleRepository;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Infrastructure\Exceptions\ServerErrorException;
use Infrastructure\Illuminate\Redis\RedisRepository;
use JetBrains\PhpStorm\ArrayShape;
use Redis;
use RedisException;
use RuntimeException;

/**
 * This class used only system bearer generate
 *
 * @method array transactionalRun(string $secret, string $domain)
 */
final class CreateGrantTokenAction extends AbstractAction
{
    /**
     * @param Client $client
     * @param OauthClientRepository $clientRepository
     * @param RoleRepository $roleRepository
     * @param PermissionRepository $permissionRepository
     * @param AttributeRepository $attributeRepository
     * @param RedisRepository $redisRepository
     */
    public function __construct(
        private readonly Client $client,
        private readonly OauthClientRepository $clientRepository,
        private readonly RoleRepository $roleRepository,
        private readonly PermissionRepository $permissionRepository,
        private readonly AttributeRepository $attributeRepository,
        private readonly RedisRepository $redisRepository
    )
    {
    }

    /**
     * @throws GuzzleException
     * @throws ServerErrorException
     * @throws RedisException
     */
    #[ArrayShape([
        'token_type' => 'string',
        'expires_in' => 'string',
        'access_token' => 'string',
    ])]
    public function run(string $secret, string $domain): array
    {
        $client = $this->clientRepository->findBySecretAndDomain($secret, $domain);

        if (!$client) {
            throw new RuntimeException("Client isn't found on this domain name : $domain", 500);
        }

        $roles = $this->roleRepository->findAllBySystemRoles($client->user_id);
        $permissions = $this->permissionRepository->findAllBySystemRoles($client->user_id);
        $attributes = $this->attributeRepository->findAllBySystemId($client->user_id);

        Cache::putMany([
            'jwtRoles' => $roles->pluck('roleValue'),
            'jwtPermissions' => $permissions->pluck('permissionValue'),
            'jwtAttributes' => $attributes->pluck('attributeValue'),
            'jwtTypes' => $attributes->pluck('resource.resourceValue'),
        ], now()->addMinute());

        $url = config('app.url') . (Str::endsWith(config('app.url'), '/') ? 'oauth/token' : '/oauth/token');
        try {
            $request = $this->client->post(
                $url,
                [
                    'form_params' => [
                        'grant_type' => 'client_credentials',
                        'client_id' => $client->id,
                        'client_secret' => $secret,
                        'scope' => '*',
                    ],
                ]
            );
            $result = json_decode($request->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $exception) {
            logging($exception);

            throw new ServerErrorException();
        }

        if (!app()->isLocal()) {
            $this->setSystemBearerBySharedStore($secret, $result);
        }

        return $result;
    }

    /**
     * @param string $secret
     * @param $result
     * @return void
     * @throws RedisException
     */
    private function setSystemBearerBySharedStore(string $secret, $result): void
    {
        $secretData = $this->redisRepository->redis->hMGet($secret, [0]);

        if ($secretData && $secretData[0]) {
            return;
        }

        $this->redisRepository->redis->hMSet($secret, [igbinary_serialize($result)]);
    }
}
