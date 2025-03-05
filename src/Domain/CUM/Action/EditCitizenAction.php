<?php

declare(strict_types=1);

namespace App\Domain\CUM\Action;

use App\Core\Abstract\AbstractAction;
use App\Domain\CUM\Http\DTO\EditCitizenDTO;
use App\Domain\CUM\Model\Citizen;
use App\Domain\CUM\Repository\CitizenRepository;
use App\Domain\UMAC\Exception\InvalidUserProfileDataException;
use App\Domain\UMAC\Model\Profile;
use App\Domain\UMAC\Repository\ProfileRepository;
use Illuminate\Support\Facades\Hash;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Service\Repository\Exceptions\RepositoryException;

/**
 * @method Profile transactionalRun(EditCitizenDTO $dto)
 */
class EditCitizenAction extends AbstractAction
{
    public function __construct(
        public readonly ProfileRepository $profileRepository,
        public readonly CitizenRepository $citizenRepository,
    )
    {
    }

    /**
     * @param EditCitizenDTO $dto
     * @return Profile
     * @throws ContainerExceptionInterface
     * @throws InvalidUserProfileDataException
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     */
    public function run(EditCitizenDTO $dto): Profile
    {
        $profile = $this->profileRepository->findByCitizenId($dto->citizenId);

        if (!$profile) {
            throw new InvalidUserProfileDataException();
        }

        $this->citizenRepository->update(
            $dto->citizenId,
            [
                'email' => $dto->email,
                'phone' => $dto->phone,
                'password' => Hash::make($dto->password),
            ]
        );

        return $profile;
    }
}
