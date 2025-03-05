<?php

declare(strict_types=1);

namespace App\Domain\Micro\Dispatch;

use App\Core\Factories\HeaderFactory;
use App\Domain\UMAC\Model\Profile;
use App\Domain\UMAC\Repository\ProfileRepository;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Bus\PendingDispatch;
use Infrastructure\Exceptions\ServerErrorException;

/**
 * @method PendingDispatch dispatchSync(int $profileId, null|array $additionalData = [])
 */
class SyncCreateCitizenApiTrigger
{
    use Dispatchable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private readonly int $profileId,
        private readonly null|array $additionalData = [],
    )
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
     */
    public function handle(ProfileRepository $profileRepository, Client $client): void
    {
        /* @var Profile $profile */
        $profile = $profileRepository->with('citizen')->find($this->profileId);

        if (!$profile) {
            return;
        }

        $data = [
            'uuid' => $profile->citizen->uuid,
            'psn' => $profile->psn ?? null,
            'documentType' => $profile->citizen?->documentType?->value ?? null,
            'documentValue' => $profile->citizen?->documentValue ?? null,
            'firstName' => $profile->firstName,
            'lastName' => $profile->lastName,
            'personType' => $profile->citizen->personType->value,
            'email' => $profile->citizen->email,
            'phone' => $profile->citizen->phone,
            'isActive' => $profile->citizen->isActive,
            'patronymic' => $profile->patronymic,
            'dateBirth' => $profile->dateBirth,
            'gender' => $profile->gender?->value ?? null,
            'avatar' => $profile->avatar,
        ];

        if (!empty($this->additionalData)) {
            $data = array_merge($data, $this->additionalData);
        }

        try {
            $client->post(config('app.service_api_url') . '/micro/citizen/store', [
                'form_params' => $data,
                'headers' => HeaderFactory::defaults(),
            ]);
        } catch (Exception | ClientException $exception) {
            logging($exception);

            throw new ServerErrorException($exception->getMessage());
        }
    }
}
