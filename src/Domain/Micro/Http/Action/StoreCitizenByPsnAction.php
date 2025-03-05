<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Action;

use App\Core\Abstract\AbstractAction;
use App\Core\Api\Ekeng\DTO\EkgDTO;
use App\Core\Enum\AccessDocumentType;
use App\Core\Enum\DocumentStatus;
use App\Core\Enum\DocumentType;
use App\Domain\CUM\Model\Citizen;
use App\Domain\CUM\Repository\CitizenRepository;
use App\Domain\Micro\Dispatch\SyncCreateCitizenApiTrigger;
use App\Domain\Micro\Http\DTO\StoreByDocumentDTO;
use App\Domain\Micro\Service\QueryService;
use App\Domain\UMAC\Exception\IdentityDocumentDoesntExistsException;
use App\Domain\UMAC\Exception\UserPsnDoesntExistsException;
use App\Domain\UMAC\Model\Profile;
use App\Domain\UMAC\Repository\DocumentRepository;
use App\Domain\UMAC\Repository\ProfileRepository;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use Infrastructure\Exceptions\ServerErrorException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Service\Repository\Exceptions\RepositoryException;

/**
 * @method Citizen|Profile transactionalRun(StoreByDocumentDTO $dto)
 */
final class StoreCitizenByPsnAction extends AbstractAction
{
    public function __construct(
        private readonly QueryService $queryService,
        private readonly CitizenRepository $citizenRepository,
        private readonly ProfileRepository $profileRepository,
        private readonly DocumentRepository $documentRepository,
    )
    {
    }

    /**
     * @param StoreByDocumentDTO $dto
     * @return Citizen|Profile
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws GuzzleException
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     * @throws ServerErrorException
     * @throws UserPsnDoesntExistsException
     * @throws IdentityDocumentDoesntExistsException
     * @throws \JsonException
     * @throws \RedisException
     */
    public function run(StoreByDocumentDTO $dto)
    {
        $address = [];
        $result = $this->queryService->getByDocumentType(
            $dto->documentValue,
            AccessDocumentType::{$dto->documentType}
        );

        if ($result instanceof EkgDTO) {
            $schema = $result;
            $result = $this->createCitizenProfile($result, $dto);

            $address = [
                'registrationRegion' => $schema->region,
                'registrationCommunity' => $schema->community,
                'registrationAddress' => $schema->registrationAddress,
            ];
        }

        if ($dto->notificationAddressOrigin) {
            $address = array_merge([
                'notificationAddressOrigin' => $dto->notificationAddressOrigin->value,
            ], $address);
        }

        if ($dto->notificationAddress) {
            $address = array_merge([
                'notificationAddressOrigin' => $dto->notificationAddressOrigin->value,
                'notificationRegion' => $dto->notificationRegion,
                'notificationCommunity' => $dto->notificationCommunity,
                'notificationAddress' => $dto->notificationAddress,
            ], $address);
        }

        SyncCreateCitizenApiTrigger::dispatchSync(
            $result->profileId,
            $address,
        );

        return $result;
    }

    /**
     * @param EkgDTO $schema
     * @param StoreByDocumentDTO $dto
     * @return Profile
     * @throws BindingResolutionException
     * @throws RepositoryException
     * @throws \JsonException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function createCitizenProfile(EkgDTO $schema, StoreByDocumentDTO $dto): Profile
    {
        $profile = $dto->documentType === AccessDocumentType::psn->value
            ? $this->profileRepository->getProfileByPsnWithCitizenUser($dto->documentValue)
            : $this->profileRepository->findByDocumentValue($dto->documentValue);

        $citizenData = [
            'email' => $dto->email,
            'phone' => $dto->phone,
            'personType' => $schema->personType->value,
        ];

        if (!$profile || !$profile->citizen) {
            $primaryDocument = collect($schema->documents)->firstWhere('isPrimary', true);
            if (!$profile) {
                /* @var Profile $profile */
                $profile = $this->profileRepository->create([
                    'firstName' => $schema->firstName,
                    'lastName' => $schema->lastName,
                    'patronymic' => $schema->patronymic,
                    'dateBirth' => $schema->dateBirth,
                    'gender' => $schema->gender,
                    'psn' => $schema->psn,
                ]);
            }
            $citizenData = array_merge($citizenData, ['profileId' => $profile->profileId]);
            $documents = [];

            foreach (collect($schema->documents)->unique('serialNumber') as $document) {
                if (DocumentType::has($document->type)) {
                    $documents[] = [
                        'documentType' => DocumentType::fromValue($document->type),
                        'documentStatus' => DocumentStatus::fromValue($document->status),
                        'serialNumber' => $document->serialNumber,
                        'citizenship' => $document->citizenship,
                        'dateIssue' => $document->dateIssue,
                        'dateExpiry' => $document->dateExpiry,
                        'authority' => $document->authority,
                        'photo' => $document?->photo,
                    ];
                }
            }

            $citizenData = array_merge($citizenData, ['documents' => $documents]);

            if (AccessDocumentType::document->value === $dto->documentType) {
                $citizenData = array_merge($citizenData, [
                    'documentType' => $schema->identifierDocumentType,
                    'documentValue' => $dto->documentValue,
                    'profileId' => $profile->profileId,
                ]);
            }

            $this->citizenRepository->create($citizenData, true);

            if ($primaryDocument) {
                $primaryDocument = $this->documentRepository->findBySerialNumber($primaryDocument->serialNumber);

                $this->profileRepository->update(
                    $profile->profileId,
                    [
                        'avatar' => $primaryDocument->photo,
                    ],
                );
            }
        }

        return $profile;
    }
}
