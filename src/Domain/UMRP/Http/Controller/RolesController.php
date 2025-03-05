<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Http\Controller;

use App\Core\Abstract\AbstractResource;
use App\Domain\UMRP\Action\Role\CreateRoleAction;
use App\Domain\UMRP\Action\UpdateRoleAction;
use App\Domain\UMRP\Http\Request\CreateRoleRequest;
use App\Domain\UMRP\Http\Request\GetRolesRequest;
use App\Domain\UMRP\Http\Request\UpdateRoleRequest;
use App\Domain\UMRP\Http\Resource\RoleInfoResource;
use App\Domain\UMRP\Queue\LogoutUsersOnRoleChange;
use App\Domain\UMRP\Service\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Infrastructure\Exceptions\ServerErrorException;
use Infrastructure\Http\Controllers\Controller;
use Infrastructure\Http\Resource\PaginateResource;
use Infrastructure\Http\Resource\RoleResource;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

final class RolesController extends Controller
{
    use dataHeader;

    /**
     * @param RoleService $roleService
     */
    public function __construct(private readonly RoleService $roleService)
    {
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function roles(): AnonymousResourceCollection
    {
        $roles = $this->roleService->findAvailableRoles();

        return RoleResource::collection($roles);
    }

    /**
     * @param GetRolesRequest $request
     * @return AbstractResource|PaginateResource
     */
    public function rolesPager(GetRolesRequest $request): AbstractResource|PaginateResource
    {
        $dto = $request->toDTO();
        $roles = $this->roleService->getRolePager($dto);

        return (new PaginateResource($roles))
            ->additional($this->getRoleHeaders())
            ->collectionClass(RoleResource::class);
    }

    /**
     * @param int $roleId
     * @return RoleInfoResource
     */
    public function infoRole(int $roleId): RoleInfoResource
    {
        $role = $this->roleService->findRoleWithPermissions($roleId);
        $free_permissions = $this->roleService->findPermissionsExceptRole($roleId);
        $role?->setAttribute('freePermissions', $free_permissions);

        return new RoleInfoResource($role);
    }

    /**
     * @param CreateRoleRequest $request
     * @param CreateRoleAction $createRoleAction
     * @return RoleResource
     */
    public function create(CreateRoleRequest $request, CreateRoleAction $createRoleAction): RoleResource
    {
        $dto = $request->toDTO();
        $role = $createRoleAction->transactionalRun($dto);

        return (new RoleResource($role))->additional(['message' => trans('roles.created')]);
    }

    /**
     * @param UpdateRoleRequest $request
     * @return RoleResource
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ServerErrorException
     */
    public function update(UpdateRoleRequest $request, UpdateRoleAction $updateRoleAction): RoleResource
    {
        $dto = $request->toDTO();
        $role = $updateRoleAction->transactionalRun($dto);
        LogoutUsersOnRoleChange::dispatch($dto->roleId);

        return (new RoleResource($role))->additional(['message' => trans('roles.updated')]);
    }

    /**
     * @param int $roleId
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface|ServerErrorException
     */
    public function delete(int $roleId): JsonResponse
    {
        $this->roleService->deleteRole($roleId);
        LogoutUsersOnRoleChange::dispatch($roleId);

        return jsponse(['message' => 'role deleted', '_payload' => ['roleId' => $roleId]]);
    }
}
