<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Action;

use App\Core\Abstract\AbstractAction;
use App\Domain\Micro\Http\DTO\LogoutDTO;
use App\Domain\Oauth\Model\Token;
use App\Domain\Oauth\Repository\OauthAccessTokenRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Infrastructure\Eloquent\Model\ClientDevice;
use Infrastructure\Eloquent\Repository\ClientDeviceRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Service\Repository\Exceptions\RepositoryException;

/**
 * @method null|ClientDevice transactionalRun(LogoutDTO $dto)
 */
final class LogoutAction extends AbstractAction
{
    public function __construct(
        private readonly OauthAccessTokenRepository $accessTokenRepository,
        private readonly ClientDeviceRepository $clientDeviceRepository,
    )
    {
    }

    /**
     * @param LogoutDTO $dto
     * @return Token|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     * @throws \JsonException
     */
    public function run(LogoutDTO $dto): ?ClientDevice
    {
        Log::info('jti:' . $dto->jti);
        /* @var Token $token */
        $token = $this->accessTokenRepository->findByIdWithClient($dto->jti);
        $provider = $token?->client?->provider;

        if ($provider) {
            /* @var Model $model */
            $model = config("auth.providers.$provider.model");
            (new $model())->update(['active' => false]);
        }

        $device = $this->accessTokenRepository->findByIdWithDevice($dto->jti);

        if ($device && $device->device) {
            $this->clientDeviceRepository->update($device->device->clientDeviceId, ['logoutAt' => now()]);
        }

        Event::dispatch('client.logout');

        $this->accessTokenRepository->revokeAccessToken($token->id);

        return $device?->device;
    }
}
