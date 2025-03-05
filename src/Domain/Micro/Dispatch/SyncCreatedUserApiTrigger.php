<?php

declare(strict_types=1);

namespace App\Domain\Micro\Dispatch;

use App\Core\Factories\HeaderFactory;
use App\Domain\UMAC\Model\Profile;
use App\Domain\UMAC\Repository\ProfileRepository;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Bus\PendingDispatch;
use Infrastructure\Exceptions\ServerErrorException;

/**
 * @method PendingDispatch dispatchSync(int $profileId)
 */
class SyncCreatedUserApiTrigger
{
    use Dispatchable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private readonly int $profileId)
    {
    }

    /**
     * Execute the job.
     *
     * @param ProfileRepository $profileRepository
     * @param Client $client
     * @return void
     * @throws GuzzleException
     * @throws ServerErrorException
     * @throws \JsonException
     */
    public function handle(ProfileRepository $profileRepository, Client $client): void
    {
        /* @var Profile $profile */
        $profile = $profileRepository->with('user')->find($this->profileId);

        if (!$profile) {
            return;
        }

        $data = [
            'uuid' => $profile->user->uuid,
            'email' => $profile->user->email,
            'phone' => $profile->user->phone,
            'psn' => $profile->psn,
            'firstName' => $profile->firstName,
            'lastName' => $profile->lastName,
            'patronymic' => $profile->patronymic,
            'dateBirth' => $profile->dateBirth,
            'gender' => $profile->gender->value,
        ];

        try {
            $client->post(config('app.service_api_url') . '/micro/user/store', [
                'form_params' => $data,
                'headers' => HeaderFactory::defaults(),
            ]);
        } catch (Exception $exception) {
            logging($exception);

            $message_decode = json_decode(
                $exception->getResponse()->getBody()->getContents(),
                false,
                512,
                JSON_THROW_ON_ERROR
            );

            if ('UUID_UNIQUE' === $message_decode->errors->uuid) {
                return;
            }

            throw new ServerErrorException();
        }
    }
}
