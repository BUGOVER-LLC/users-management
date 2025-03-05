<?php

declare(strict_types=1);

namespace App\Domain\UMAA\Action;

use App\Core\Abstract\AbstractAction;
use App\Domain\UMAC\Model\User;
use App\Domain\UMAC\Repository\ProfileRepository;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Infrastructure\Exceptions\ServerErrorException;

/**
 * @method array transactionalRun($bearer, User $user)
 */
final class SendDataToApiAction extends AbstractAction
{
    public function __construct(private readonly Client $client, private readonly ProfileRepository $profileRepository)
    {
    }

    /**
     * @param $bearer
     * @param User $user
     * @return array
     * @throws GuzzleException
     * @throws ServerErrorException
     */
    public function run($bearer, User $user)
    {
        $data = [
            'accessToken' => $bearer['accessToken'],
            'refreshToken' => $bearer['refreshToken'],
            'tokenType' => $bearer['tokenType'],
            'expiresIn' => $bearer['expiresIn'],
            'uuid' => $user->uuid,
        ];

        $this->sendAuthData($user);
        $this->sendAuthBearer($data);

        return $data;
    }

    /**
     * @param User $user
     * @throws GuzzleException
     * @throws ServerErrorException
     */
    private function sendAuthData(User $user): void
    {
        $profile = $this->profileRepository->find($user->profileId);

        if (!$profile) {
            throw new ServerErrorException();
        }

        $data = [
            'psn' => $profile->psn,
            'uuid' => $user->uuid,
            'email' => $user->email,
            'firstName' => $profile->firstName,
            'lastName' => $profile->lastName,
            'patronymic' => $profile->patronymic,
            'dateBirth' => $profile->dateBirth,
            'gender' => $profile->gender->value,
            'isActive' => $user->active,
        ];

        try {
            $this->client->post(
                config('app.service_api_url') . '/micro/user/store',
                [
                    'connect_timeout' => '5',
                    'timeout' => '10',
                    'form_params' => $data,
                    'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest'],
                ]
            );
        } catch (Exception | ClientException $exception) {
            logging($exception, 'micro');

            throw new ServerErrorException();
        }
    }

    /**
     * @param array $data
     * @return void
     * @throws GuzzleException
     * @throws ServerErrorException
     */
    private function sendAuthBearer(array $data): void
    {
        try {
            $this->client->post(
                config('app.service_api_url') . '/micro/login',
                [
                    'connect_timeout' => '5',
                    'timeout' => '10',
                    'form_params' => $data,
                    'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest'],
                ]
            );
        } catch (Exception $exception) {
            logging($exception);

            throw new ServerErrorException();
        }
    }
}
