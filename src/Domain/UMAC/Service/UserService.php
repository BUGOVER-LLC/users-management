<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Service;

use App\Domain\UMAC\Enum\PersonType;
use App\Domain\UMAC\Http\DTO\EditUserDTO;
use App\Domain\UMAC\Http\DTO\UserPagerDTO;
use App\Domain\UMAC\Model\Profile;
use App\Domain\UMAC\Model\User;
use App\Domain\UMAC\Repository\InvitationUserRepository;
use App\Domain\UMAC\Repository\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Infrastructure\Eloquent\Repository\ClientUserMappingRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Service\Repository\Exceptions\RepositoryException;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly InvitationUserRepository $invitationUserRepository,
        private readonly ClientUserMappingRepository $mappingRepository,
    )
    {
    }

    /**
     * @param int $userId
     */
    public function deleteUserInvitation(int $userId): void
    {
        $userUpdate = $this->userRepository->find($userId);

        DB::transaction(function () use ($userId, $userUpdate) {
            $delete = $this->invitationUserRepository
                ->where('userId', '=', $userId)
                ->deletes();

            if (!$delete) {
                $this->mappingRepository
                    ->where('systemId', '=', Auth::user()->currentSystemId)
                    ->where('userId', '=', $userId)
                    ->where('userType', '=', User::map())
                    ->deletes();

                Event::dispatch('user.token.revoke', [$userUpdate, Auth::user()->currentSystemId]);
            }
        });
    }

    /**
     * @param string $psn
     * @return ?Profile
     */
    public function findUserProfileByPsn(string $psn): ?Profile
    {
        return $this->userRepository
            ->whereHas('profile', fn(Builder $qb) => $qb->where('psn', '=', $psn))
            ->with('profile')
            ->findFirst()
            ?->profile;
    }

    /**
     * @param string $psn
     * @param int $currentSystemId
     * @return bool
     */
    public function checkUserexistsByPsnAndInSystem(string $psn, int $currentSystemId): bool
    {
        $user = $this->findUserByPsn($psn);

        return $user && $this->mappingRepository->hasExistsUserInSystem(
            systemId: $currentSystemId,
            userId: $user->userId,
            userMap: $user->getMap(),
        );
    }

    /**
     * @param string $psn
     * @return User|null
     */
    public function findUserByPsn(string $psn): ?User
    {
        return $this->userRepository
            ->whereHas('profile', fn(Builder $qb) => $qb->where('psn', '=', $psn))
            ->with('profile')
            ->findFirst();
    }

    /**
     * @param UserPagerDTO $dto
     * @return LengthAwarePaginator
     */
    public function getUserInvitationPager(UserPagerDTO $dto): LengthAwarePaginator
    {
        return $this->userRepository
            ->when(
                value: $dto->personType === PersonType::user->value,
                callback: fn(Builder $qb) => $qb
                    ->whereHas(
                        relation: 'mapping',
                        callback: fn(Builder $qb) => $qb
                            ->where('systemId', '=', $dto->getUser()->currentSystemId)
                    )
            )
            ->when(
                $dto->roles && count($dto->roles),
                fn(Builder $qb) => $qb->whereIn('roleId', !\is_array($dto->roles) ? [$dto->roles] : $dto->roles)
            )
            ->getUserPagerBuilder($dto)
            ->paginate($dto->perPage, ['*'], 'page', $dto->page);
    }

    /**
     * @param string $roleValue
     * @return Collection<User>
     */
    public function getUsersByRoleValue(string $roleValue): Collection
    {
        return $this->userRepository
            ->has('profile')
            ->with('role', 'profile')
            ->whereHas('role', fn($qb) => $qb->where('hasSubordinates', '=', true))
            ->findAll();
    }

    /**
     * @param EditUserDTO $dto
     * @return User
     * @throws \JsonException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     */
    public function editUser(EditUserDTO $dto): User
    {
        $userUpdate = $this->userRepository->update($dto->userId, [
            'email' => $dto->email,
            'active' => $dto->active,
            'roleId' => $dto->roleId,
            'attributeId' => $dto->attributeId,
            'parentId' => $dto->parentId,
        ]);

        Event::dispatch('user.token.revoke', [$userUpdate, Auth::user()->currentSystemId]);

        return $this->userRepository->with('profile')->find($dto->userId);
    }

    /**
     * @param int $userId
     * @return Collection
     */
    public function getAllUserInvitations(int $userId): Collection
    {
        return $this->invitationUserRepository->where('userId', '=', $userId)->withTrashed()->findAll();
    }
}
