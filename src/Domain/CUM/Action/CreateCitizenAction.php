<?php

declare(strict_types=1);

namespace App\Domain\CUM\Action;

use App\Core\Abstract\AbstractAction;
use App\Core\Api\Ekeng\DTO\EkgPassportDTO;
use App\Core\Api\Ekeng\EkengApi;
use App\Core\Enum\AccessDocumentType;
use App\Core\Enum\DocumentStatus;
use App\Core\Enum\DocumentType;
use App\Core\Enum\Gender;
use App\Core\Enum\PersonType;
use App\Domain\CUM\Model\Citizen;
use App\Domain\CUM\Repository\CitizenRepository;
use App\Domain\UMAC\Exception\UserPsnDoesntExistsException;
use App\Domain\UMAC\Model\Profile;
use App\Domain\UMAC\Repository\DocumentRepository;
use App\Domain\UMAC\Repository\ProfileRepository;
use App\Domain\UMAC\Service\UserService;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Infrastructure\Exceptions\ServerErrorException;
use JsonException;
use Service\Repository\Exceptions\RepositoryException;

/**
 * @method Profile transactionalRun(array $citizenInfo)
 */
final class CreateCitizenAction extends AbstractAction
{
    public function __construct(
        public readonly EkengApi $ekengApi,
        public readonly UserService $userService,
        public readonly ProfileRepository $profileRepository,
        public readonly CitizenRepository $citizenRepository,
        public readonly DocumentRepository $documentRepository,
        private readonly Client $client,
    )
    {
    }

    /**
     * @param array $arguments
     *
     * @return Profile
     *
     * @throws BindingResolutionException
     * @throws RepositoryException
     * @throws UserPsnDoesntExistsException
     * @throws ServerErrorException
     * @throws GuzzleException
     */
    public function run(array $arguments): Profile
    {
        return $this->createOrExistsCitizen($arguments);
    }

    /**
     * @param $citizenInfo
     *
     * @return Profile
     *
     * @throws BindingResolutionException
     * @throws RepositoryException
     * @throws UserPsnDoesntExistsException
     * @throws ServerErrorException
     * @throws GuzzleException
     * @throws JsonException
     */
    private function createOrExistsCitizen($citizenInfo): Profile
    {
        // TODO refactor and optimize, bayc es kanem)
        $psn = (string) $citizenInfo['psn'];
        $citizenProfile = $this->profileRepository->getProfileByPsnWithCitizen($psn);
        $address = [];

        if (!$citizenProfile?->citizen) {
            $userProfile = $this->userService->findUserProfileByPsn($psn);
            $ekengSchema = $this->ekengApi->send($psn, AccessDocumentType::psn)->getPersonInfo();
            $address = [
                'registrationRegion' => $ekengSchema->region,
                'registrationCommunity' => $ekengSchema->community,
                'registrationAddress' => $ekengSchema->registrationAddress,
            ];

            if ($userProfile) {
                $citizen = $this->citizenRepository->create([
                    'profileId' => $userProfile->profileId,
                    'userId' => $userProfile->user->userId,
                    'isActive' => true,
                    'isChecked' => true,
                    'personType' => PersonType::RESIDENT,
                ]);
                $citizenProfile = $citizen->profile;
            } else {
                /* @var Profile<Citizen> $citizenProfile */
                $citizenProfile = $this->profileRepository->create(
                    attrs: [
                        'psn' => $psn,
                        'firstName' => $ekengSchema->firstName,
                        'lastName' => $ekengSchema->lastName,
                        'patronymic' => $ekengSchema->patronymic,
                        'gender' => Gender::fromValue($ekengSchema->gender),
                        'dateBirth' => $ekengSchema->dateBirth,
                        'citizen' => [
                            'isActive' => true,
                            'isChecked' => true,
                            'personType' => PersonType::RESIDENT,
                        ],
                    ],
                    sync_relations: true,
                );

                /* @var EkgPassportDTO $document */
                foreach ($ekengSchema->documents as $document) {
                    $this->documentRepository->create([
                        'citizenId' => $citizenProfile->citizen->citizenId,
                        'documentType' => DocumentType::fromValue($document->type),
                        'documentStatus' => DocumentStatus::fromValue($document->status),
                        'serialNumber' => $document->serialNumber,
                        'citizenship' => $document->citizenship,
                        'dateIssue' => $document->dateIssue,
                        'dateExpiry' => $document->dateExpiry,
                        'authority' => $document->authority,
                        'photo' => $document->photo,
                    ]);
                }
            }
        }

        $this->sendForAuth($citizenProfile, $address);

        return $citizenProfile;
    }

    /**
     * @throws ServerErrorException
     * @throws GuzzleException
     * @throws JsonException
     */
    private function sendForAuth(Profile $profile, array $address): void
    {
        $data = array_merge(
            [
                'psn' => $profile->psn,
                'documentType' => $profile->citizen?->documentType?->value,
                'documentValue' => $profile->citizen?->documentValue,
                'uuid' => $profile->citizen->uuid,
                'firstName' => $profile->firstName,
                'lastName' => $profile->lastName,
                'personType' => $profile->citizen->personType->value,
                'email' => $profile->citizen->email,
                'phone' => $profile->citizen->phone,
                'isActive' => $profile->citizen->isActive,
                'patronymic' => $profile->patronymic,
                'dateBirth' => $profile->dateBirth,
                'gender' => $profile->gender->value,
            ],
            $address,
        );

        try {
            $this->client->post(
                config('app.service_api_url') . '/micro/citizen/store',
                [
                    'form_params' => $data,
                    'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest'],
                ]
            );
        } catch (Exception | ClientException $exception) {
            if ($exception instanceof ClientException) {
                $message_decode = json_decode(
                    $exception->getResponse()->getBody()->getContents(),
                    false,
                    512,
                    JSON_THROW_ON_ERROR
                );
                if (($message_decode?->errors ?? false) && 'UUID_UNIQUE' === $message_decode->errors?->uuid ?? null) {
                    return;
                }
            }

            logging($exception, 'micro');

            throw new ServerErrorException();
        }
    }
}
