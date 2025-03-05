<?php

declare(strict_types=1);

namespace App\Domain\CUM\Action;

use App\Core\Abstract\AbstractAction;
use App\Core\Utils\Devicer;
use App\Domain\UMAC\Model\User;
use App\Domain\UMAC\Repository\UserRepository;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Infrastructure\Eloquent\Repository\ClientDeviceRepository;
use Infrastructure\Exceptions\ServerErrorException;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @method array transactionalRun(string $uuid)
 */
class CreateUserPersonalAccessTokenAction extends AbstractAction
{
    public function __construct(
        private readonly Client $client,
        private readonly UserRepository $userRepository,
        private readonly ClientDeviceRepository $clientDeviceRepository
    )
    {
    }

    /**
     * @param string $uuid
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws ServerErrorException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function run(string $uuid): array
    {
        Cache::putMany([
            'jwtDevice' => (new Devicer())->deviceIdentifier(),
        ], now()->addMinute());

        try {
            /* @var User $user */
            $user = $this->userRepository->findByUuid($uuid)?->profile;
            $token = $user->createToken($user->email);
            $accessToken = $token->accessToken;
            $expiresIn = $token->token->expires_at->diffInSeconds(Carbon::now());
            $formData = [
                'tokenType' => 'Bearer',
                'expiresIn' => $expiresIn,
                'accessToken' => $accessToken,
                'refreshToken' => null,
                'type' => 'nid',
                'uuid' => $user->uuid,
            ];

            $this->sendForLogin($formData);
            $this->clientDeviceRepository->persistDevice($user->userId, $user->getMap());

            return $formData;
        } catch (Exception $exception) {
            logging($exception);

            throw new ServerErrorException();
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

            throw new ServerErrorException();
        }
    }
}
