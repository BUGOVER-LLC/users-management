<?php

declare(strict_types=1);

namespace App\Domain\UMAA\Action;

use App\Core\Abstract\AbstractAction;
use App\Domain\UMAA\Exception\InvitationDoesntExists;
use App\Domain\UMAA\Http\DTO\AcceptInviteUserDTO;
use App\Domain\UMAC\Model\User;
use App\Domain\UMAC\Repository\InvitationUserRepository;
use App\Domain\UMAC\Repository\ProfileRepository;
use App\Domain\UMAC\Repository\UserRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Hash;
use Infrastructure\Eloquent\Repository\ClientDeviceRepository;
use JetBrains\PhpStorm\ArrayShape;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Service\Repository\Exceptions\RepositoryException;

/**
 * @method User transactionalRun(AcceptInviteUserDTO $dto)
 */
final class InviteToUserAction extends AbstractAction
{
    /**
     * @param UserRepository $userRepository
     * @param ProfileRepository $profileRepository
     * @param InvitationUserRepository $invitationRepository
     * @param ClientDeviceRepository $clientDeviceRepository
     */
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ProfileRepository $profileRepository,
        private readonly InvitationUserRepository $invitationRepository,
        private readonly ClientDeviceRepository $clientDeviceRepository
    )
    {
    }

    /**
     * @param AcceptInviteUserDTO $dto
     * @return User
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws InvitationDoesntExists
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     * @throws \JsonException
     */
    #[ArrayShape([
        'expires_in' => 'string',
        'token_type' => 'string',
        'access_token' => 'string',
        'refresh_token' => 'string',
    ])]
    public function run(AcceptInviteUserDTO $dto): User
    {
        $invitation = $this->invitationRepository->getPendingActiveInviteWithUser($dto->token);

        if (!$invitation || !$invitation->user) {
            throw new InvitationDoesntExists();
        }

        $profile = $this->profileRepository->findByPsn($invitation->psnInfo['psn']);

        if (!$profile) {
            $profile = $this->profileRepository->create($invitation->psnInfo);
        }

        $this->userRepository->update($invitation->userId, [
            'password' => Hash::make($dto->password),
            'profileId' => $profile->profileId,
            'active' => true,
        ]);

        $this->clientDeviceRepository->persistDevice($invitation->userId, User::map());

        $this->invitationRepository->update($invitation->invitationUserId, ['acceptedAt' => now()]);
        $this->invitationRepository->delete($invitation->invitationUserId);

        return $this->userRepository->find($invitation->userId);
    }
}
