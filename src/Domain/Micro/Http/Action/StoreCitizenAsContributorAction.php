<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Action;

use App\Core\Abstract\AbstractAction;
use App\Core\Enum\DocumentType;
use App\Domain\Micro\Dispatch\SyncCreateCitizenApiTrigger;
use App\Domain\Micro\Http\DTO\StoreCitizenDTO;
use App\Domain\UMAC\Model\Profile;
use App\Domain\UMAC\Repository\ProfileRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use Service\Repository\Exceptions\RepositoryException;

/**
 * @method transactionalRun(StoreCitizenDTO $dto)
 */
final class StoreCitizenAsContributorAction extends AbstractAction
{
    public function __construct(
        private readonly ProfileRepository $profileRepository,
    )
    {
    }

    /**
     * @param StoreCitizenDTO $dto
     * @return Profile|null
     * @throws BindingResolutionException
     * @throws RepositoryException
     */
    public function run(StoreCitizenDTO $dto)
    {
        $profile = $this->profileRepository->findByDocumentValue($dto->documentValue);

        if (!$profile) {
            $profile = $this->profileRepository->create([
                'firstName' => $dto->firstName,
                'lastName' => $dto->lastName,
                'patronymic' => $dto->patronymic,
                'dateBirth' => $dto->dateBirth,
                'gender' => $dto->gender,
                'citizen' => [
                    'email' => $dto->email,
                    'phone' => $dto->phone,
                    'personType' => $dto->personType,
                    'documentType' => DocumentType::caseExists($dto->documentType) ? $dto->documentType : null,
                    'documentValue' => $dto->documentValue,
                ]
            ], true);
        }

        SyncCreateCitizenApiTrigger::dispatchSync($profile->profileId);

        return $profile;
    }
}
