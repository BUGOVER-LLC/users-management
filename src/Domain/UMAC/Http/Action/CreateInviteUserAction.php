<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Http\Action;

use App\Core\Abstract\AbstractAction;
use App\Core\Enum\EmailType;
use App\Domain\Producer\Queue\SendMailQueue;
use App\Domain\UMAC\Http\DTO\CreateUserDTO;
use App\Domain\UMAC\Model\InvitationUser;
use App\Domain\UMAC\Model\User;
use App\Domain\UMAC\Repository\InvitationUserRepository;
use App\Domain\UMAC\Repository\UserRepository;
use App\Domain\UMRP\Model\Role;
use App\Domain\UMRP\Repository\RoleRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;
use Infrastructure\Eloquent\Repository\ClientUserMappingRepository;
use Infrastructure\Illuminate\Redis\RedisRepository;
use JetBrains\PhpStorm\ArrayShape;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RedisException;
use Service\Repository\Exceptions\RepositoryException;

/**
 * @method null|User transactionalRun(CreateUserDTO $dto)
 */
final class CreateInviteUserAction extends AbstractAction
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly InvitationUserRepository $invitationUserRepository,
        private readonly ClientUserMappingRepository $userMappingRepository,
        private readonly RedisRepository $redisRepository,
        private readonly RoleRepository $roleRepository
    )
    {
    }

    /**
     * @param CreateUserDTO $dto
     * @return User|null
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RedisException
     * @throws RepositoryException
     * @throws \JsonException
     */
    public function run(CreateUserDTO $dto): ?User
    {
        $user = $this->createOrExistsUser($dto);
        $invitation = $this->createInviteUrl($dto);
        $this->createInvitation($user, $dto, $invitation);

        SendMailQueue::dispatch(EmailType::inviteUser->value, $dto->email, $invitation);
        $this->redisRepository->deleteProfileInToRedis((string) $dto->psn);

        return $this->userRepository->findUserByEmailWithInvitation($dto->email);
    }

    /**
     * @param CreateUserDTO $dto
     * @return User
     * @throws BindingResolutionException
     * @throws RepositoryException
     * @throws \JsonException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function createOrExistsUser(CreateUserDTO $dto): User
    {
        $user = $this->userRepository->findUserByEmailWithProfile($dto->email);

        if (!$user) {
            $user = $this->userRepository->create([
                'email' => $dto->email,
                'active' => $dto->active,
                'roleId' => $dto->roleId,
                'attributeId' => $dto->attributeId,
                'parentId' => $dto->parentId,
            ]);
        }

        return $user;
    }

    /**
     * @param CreateUserDTO $dto
     * @return array{domain: string,  token: string, query: string}
     */
    #[ArrayShape([
        'domain' => 'string',
        'token' => 'string',
        'query' => 'string',
    ])]
    private function createInviteUrl(CreateUserDTO $dto): array
    {
        /* @var ?Role $role */
        $role = $this->roleRepository->find($dto->roleId);

        $invite_token = Str::random(128);
        $query_string = http_build_query(data: [
            'token' => $invite_token,
            'email' => $dto->email,
            'role' => $role->roleName ?? null,
        ], encoding_type: PHP_QUERY_RFC3986);

        return [
            'domain' => config('services.invites_endpoint.user') . '/invitation',
            'token' => $invite_token,
            'query' => $query_string,
        ];
    }

    /**
     * @param User $user
     * @param CreateUserDTO $dto
     * @param array $invite_data
     * @return InvitationUser
     * @throws BindingResolutionException
     * @throws RedisException
     * @throws RepositoryException
     */
    private function createInvitation(User $user, CreateUserDTO $dto, array $invite_data): InvitationUser
    {
        $invitation = $this->invitationUserRepository->findByUserId($user->userId);
        $info = $this->redisRepository->getProfileInToRedisByPsn((string) $dto->psn);

        if ($invitation) {
            $invitation->delete();
            $this->invitationUserRepository->forgetCache();
        }

        $psn_info = [
            'psn' => $dto->psn,
            'firstName' => $dto->firstName,
            'lastName' => $dto->lastName,
            'patronymic' => $info->patronymic,
            'gender' => $info->gender,
            'dateBirth' => Carbon::parse($dto->dateBirth)->toDateString(),
        ];

        return $this->invitationUserRepository->create([
            'userId' => $user->userId,
            'inviteUrl' => $invite_data['domain'] . '?' . $invite_data['query'],
            'inviteToken' => $invite_data['token'],
            'inviteEmail' => $dto->email,
            'passed' => now()->toDateTimeString(),
            'psnInfo' => $psn_info,
        ]);
    }
}
