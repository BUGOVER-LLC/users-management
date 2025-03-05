<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Http\Controller;

use App\Core\Api\Ekeng\EkengApi;
use App\Core\Enum\AccessDocumentType;
use App\Domain\UMAC\Enum\PersonType;
use App\Domain\UMAC\Exception\IdentityDocumentDoesntExistsException;
use App\Domain\UMAC\Exception\UserPsnAlreadyExistsException;
use App\Domain\UMAC\Exception\UserPsnDoesntExistsException;
use App\Domain\UMAC\Http\Action\CreateInviteUserAction;
use App\Domain\UMAC\Http\Request\CreateUserRequest;
use App\Domain\UMAC\Http\Request\EditUserRequest;
use App\Domain\UMAC\Http\Request\UserPaginateRequest;
use App\Domain\UMAC\Http\Response\UserCheckResource;
use App\Domain\UMAC\Http\Response\UserPagerResponse;
use App\Domain\UMAC\Service\UserService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Exceptions\ServerErrorException;
use Infrastructure\Http\Controllers\Controller;
use Infrastructure\Http\Resource\PaginateResource;
use Infrastructure\Http\Resource\UserInvitationResource;
use Infrastructure\Http\Resource\UserProfileResource;
use Infrastructure\Illuminate\Redis\RedisRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RedisException;
use Service\Repository\Exceptions\RepositoryException;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends Controller
{
    use DataHeaders;

    public function __construct(
        private readonly UserService $userService,
        private readonly RedisRepository $redisRepository,
    )
    {
    }

    /**
     * @param string $roleValue
     * @return AnonymousResourceCollection
     */
    #[Route(
        path: 'users/{roleValue}',
        name: 'user.role-by-users',
        requirements: ['roleValue' => '\S+'],
        methods: ['GET']
    )]
    public function usersByRole(string $roleValue): AnonymousResourceCollection
    {
        $users = $this->userService->getUsersByRoleValue($roleValue);

        return UserProfileResource::collection($users)->additional(['message' => 'success']);
    }

    /**
     * @param CreateUserRequest $request
     * @param CreateInviteUserAction $action
     * @return PaginateResource
     */
    #[Route(path: 'create', methods: ['POST'], name: 'user.create')]
    public function createInvite(CreateUserRequest $request, CreateInviteUserAction $action): PaginateResource
    {
        $dto = $request->toDTO();
        $action->transactionalRun($dto);
        $paginateRequest = (new UserPaginateRequest())->replace([
            'page' => 1,
            'per_page' => 15,
            'person' => PersonType::invitation->value,
            'active' => true,
            'search' => '',
        ]);

        return $this->userInvitations($paginateRequest);
    }

    /**
     * @param UserPaginateRequest $request
     * @return PaginateResource
     */
    #[Route(path: 'users', methods: ['GET'], name: 'users')]
    public function userInvitations(UserPaginateRequest $request): PaginateResource
    {
        $dto = $request->toDTO();
        $result = $this->userService->getUserInvitationPager($dto);

        return (new PaginateResource($result))->additional(
            PersonType::invitation->value === $dto->personType ? $this->getDataHeadersInvitations(
            ) : $this->getDataHeadersProfile()
        )->collectionClass(UserPagerResponse::class);
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    #[Route(
        path: 'user/invite/{userId}',
        name: 'user.invite.userId',
        requirements: ['userId' => '\D+'],
        methods: ['DELETE']
    )]
    public function deleteInvite(int $userId): JsonResponse
    {
        $this->userService->deleteUserInvitation($userId);

        return jsponse(['message' => trans('users.user_delete')]);
    }

    /**
     * @param int $psn
     * @param EkengApi $action
     * @return UserCheckResource
     * @throws GuzzleException
     * @throws IdentityDocumentDoesntExistsException
     * @throws RedisException
     * @throws ServerErrorException
     * @throws UserPsnAlreadyExistsException
     * @throws UserPsnDoesntExistsException
     * @throws \JsonException
     */
    #[Route(path: 'user/check/{psn}', name: 'user.check.psn', requirements: ['psn' => '\D+'], methods: ['GET'])]
    public function userCheck(int $psn, EkengApi $action): UserCheckResource
    {
        if ($this->userService->checkUserexistsByPsnAndInSystem((string) $psn, Auth::user()->currentSystemId)) {
            throw new UserPsnAlreadyExistsException((string) $psn);
        }

        $resource = $action->send($psn, AccessDocumentType::psn)->getPersonInfo();
        $this->redisRepository->setProfileInToRedis((string) $psn, $resource);

        return new UserCheckResource($resource);
    }

    /**
     * @param EditUserRequest $request
     * @param int $userId
     * @return UserPagerResponse
     * @throws \JsonException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     */
    #[Route(path: 'user/edit/{userId}', name: 'edit.user', requirements: ['userId' => '\D+'])]
    public function editUser(EditUserRequest $request, int $userId): UserPagerResponse
    {
        $dto = $request->toDTO();
        $user = $this->userService->editUser($dto);

        return (new UserPagerResponse($user))->additional(['message' => 'success edited']);
    }

    /**
     * @param int $userId
     * @return AnonymousResourceCollection
     */
    #[Route(path: 'umac/user-invitations/{userId}', name: 'user-invitations', requirements: ['userId' => '\D+'])]
    public function getInvitations(int $userId): AnonymousResourceCollection
    {
        $invitations = $this->userService->getAllUserInvitations($userId);

        return UserInvitationResource::collection($invitations);
    }
}
