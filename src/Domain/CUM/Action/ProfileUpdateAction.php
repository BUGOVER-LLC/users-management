<?php

declare(strict_types=1);

namespace App\Domain\CUM\Action;

use App\Core\Abstract\AbstractAction;
use App\Domain\CUM\Http\DTO\ProfileUpdateDTO;
use App\Domain\CUM\Model\Citizen;
use App\Domain\CUM\Repository\CitizenRepository;
use App\Domain\Micro\Dispatch\SyncCreateCitizenApiTrigger;
use App\Domain\Micro\Dispatch\SyncCreatedUserApiTrigger;
use App\Domain\UMAC\Repository\UserRepository;
use Illuminate\Support\Facades\Auth;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Service\Repository\Exceptions\RepositoryException;

/**
 * @method transactionalRun(ProfileUpdateDTO $dto)
 */
final class ProfileUpdateAction extends AbstractAction
{
    public function __construct(
        protected readonly CitizenRepository $citizenRepository,
        protected readonly UserRepository $userRepository,
    )
    {
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     * @throws ContainerExceptionInterface
     * @throws \JsonException
     */
    public function run(ProfileUpdateDTO $dto)
    {
        if (Auth::user() instanceof Citizen) {
            $person = $this->citizenRepository->find($dto->citizenId);
            $result = $this->citizenRepository->update(
                id: $dto->citizenId,
                attrs: [
                    'phone' => $dto->phone,
                    'email' => $dto->email,
                ],
            );

            SyncCreateCitizenApiTrigger::dispatchSync(
                profileId: $person->profile->profileId,
                additionalData: [
                    'notificationAddressOrigin' => $dto->notificationAddressOrigin->value,
                    'notificationRegion' => $dto->notificationRegion,
                    'notificationCommunity' => $dto->notificationCommunity,
                    'notificationAddress' => $dto->notificationAddress,
                ],
            );
        } else {
            $person = $this->userRepository->find($dto->citizenId);
            $result = $this->userRepository->update(
                id: $dto->citizenId,
                attrs: [
                    'phone' => $dto->phone,
                    'email' => $dto->email,
                ],
            );

            SyncCreatedUserApiTrigger::dispatchSync(
                profileId: $person->profile->profileId,
            );
        }

        return $result;
    }
}
