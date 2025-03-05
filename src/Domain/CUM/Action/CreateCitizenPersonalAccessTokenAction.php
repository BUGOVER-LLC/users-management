<?php

declare(strict_types=1);

namespace App\Domain\CUM\Action;

use App\Core\Abstract\AbstractAction;
use App\Core\Utils\MachineId;
use App\Domain\CUM\Model\Citizen;
use App\Domain\CUM\Repository\CitizenRepository;
use App\Domain\Oauth\Repository\OauthAccessTokenRepository;
use App\Domain\UMAC\Model\User;
use App\Domain\UMAC\Repository\ProfileRepository;
use App\Domain\UMAC\Repository\UserRepository;
use App\Domain\UMRA\Repository\AttributeRepository;
use App\Domain\UMRP\Repository\RoleRepository;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Infrastructure\Eloquent\Repository\ClientDeviceRepository;
use Infrastructure\Exceptions\ServerErrorException;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Service\Repository\Exceptions\RepositoryException;

use function get_class;

/**
 * @method array transactionalRun(string $uuid, bool $switchingAccount = false, ?string $machineDevice = null)
 */
final class CreateCitizenPersonalAccessTokenAction extends AbstractAction
{
    public function __construct(
        private readonly Client $client,
        protected CitizenRepository $citizenRepository,
        protected ProfileRepository $profileRepository,
        private readonly ClientDeviceRepository $clientDeviceRepository,
        private readonly RoleRepository $roleRepository,
        private readonly AttributeRepository $attributeRepository,
        private readonly UserRepository $userRepository,
        private readonly OauthAccessTokenRepository $accessTokenRepository,
    )
    {
    }

    /**
     * @param string $uuid
     * @param bool $switchingAccount
     * @return array
     *
     * @throws ContainerExceptionInterface
     * @throws GuzzleException
     * @throws NotFoundExceptionInterface
     * @throws ServerErrorException
     * @throws BindingResolutionException
     * @throws JsonException
     * @throws RepositoryException
     */
    public function run(string $uuid, bool $switchingAccount = false, ?string $machineDevice = null): array
    {
        $profile = $this->profileRepository->findCurrentProfileByUuid($uuid);

        if (!$profile) {
            throw new RuntimeException('Profile not found', 500);
        }

        if ($profile->user?->uuid === $uuid) {
            $account = $profile->user;
        } else {
            $account = $profile->citizen;
        }

        $device = $machineDevice ?: MachineId::instance()->getDeviceName();
        $this->clientDeviceRepository->persistDevice(
            clientId: $account->getKey(),
            clientType: $account->getMap(),
            device: $device,
        );

        try {
            $this->setDeviceRoles($account, $device);
//            try { // @TODO think about it
//                $this->accessTokenRepository->where('user_id', '=', $account->{$account->getAuthIdentifierName()})->deletes();
//                $account->tokens()->where('user_id', '=', $account->{$account->getAuthIdentifierName()})->delete();
//            } catch (Exception $exception) {
//                Log::info('removeToken:' . $exception->getMessage());
//            }
            $token = $account->createToken($account->email);
            $accessToken = $token->accessToken;
            $expiresIn = $token->token->expires_at->diffInSeconds(Carbon::now()->toDateTimeString());
            $formData = [
                'tokenType' => 'Bearer',
                'expiresIn' => $expiresIn,
                'accessToken' => $accessToken,
                'refreshToken' => null,
                'type' => 'nid',
                'uuid' => $account->uuid,
            ];

            if ($switchingAccount) {
                $this->profileRepository->update(
                    $profile->profileId,
                    [
                        'currentLoginType' => $account::map(),
                        'currentLoginId' => $account->getKey(),
                    ],
                );
            }

            $this->sendForLogin($formData);

            return $formData;
        } catch (Exception $exception) {
            logging($exception);

            throw new ServerErrorException();
        }
    }

    /**
     * @param User|Citizen $account
     * @param string|null $device
     * @return void
     */
    private function setDeviceRoles(User|Citizen $account, ?string $device): void
    {
        Cache::putMany([
            'jwtDevice' => $device,
        ], now()->addMinute());

        if (get_class($account) === User::class) {
            $role = $this->roleRepository->findWithPermissions($account->roleId);
            $attribute = $this->attributeRepository->findByUserSystemId($account->userId);

            if (!$attribute && $account->parentId) {
                $attribute = $this->userRepository->findParentAttribute($account->parentId);
                $uuid = $this->userRepository->find($account->parentId)?->uuid;
            }

            Cache::putMany([
                'jwtUUID' => $uuid ?? $account->uuid,
                'jwtRoles' => [$role?->roleValue],
                'jwtPermissions' => $role?->permissions->pluck('permissionValue')->toArray(),
                'jwtAttributes' => [$attribute?->attributeValue],
                'jwtTypes' => [$attribute?->resource?->resourceValue],
            ], now()->addMinute());
        }
    }

    /**
     * @throws ServerErrorException
     * @throws GuzzleException
     */
    private function sendForLogin($form_data): void
    {
        try {
            $this->client->post(config('app.service_api_url') . '/micro/login', [
                'form_params' => $form_data,
                'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest'],
            ]);
        } catch (Exception $exception) {
            logging($exception);

            throw new ServerErrorException($exception->getMessage(), $exception->getCode());
        }
    }
}
