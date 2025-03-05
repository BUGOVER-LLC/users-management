<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Http\Controller;

use App\Core\Enum\Accessor;
use App\Domain\UMRP\Http\Request\CreatePermissionRequest;
use App\Domain\UMRP\Http\Request\GetPermissionRequest;
use App\Domain\UMRP\Http\Request\UpdatePermissionRequest;
use App\Domain\UMRP\Service\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Infrastructure\Exceptions\ServerErrorException;
use Infrastructure\Http\Controllers\Controller;
use Infrastructure\Http\Resource\PaginateResource;
use Infrastructure\Http\Resource\PermissionResource;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

final class PermissionController extends Controller
{
    public function __construct(
        private readonly RoleService $roleService
    )
    {
    }

    public function permissions(GetPermissionRequest $request)
    {
        $dto = $request->toDTO();

        $permissions = $this->roleService->getAllPermissions($dto);
        $headers = [
            'headers' => [
                [
                    'text' => 'Հ/Հ',
                    'value' => 'permissionId',
                    'sortable' => true,
                ],
                [
                    'text' => __('producer.permission_name'),
                    'value' => 'permissionName',
                    'sortable' => false,
                ],
                [
                    'text' => __('producer.permission_value'),
                    'value' => 'permissionValue',
                    'sortable' => false,
                ],
                [
                    'text' => __('producer.permission_description'),
                    'value' => 'permissionDescription',
                    'sortable' => false,
                ],
                [
                    'text' => __('producer.is_active'),
                    'value' => 'permissionActive',
                    'sortable' => false,
                ],
                [
                    'text' => __('producer.options'),
                    'value' => 'options',
                    'sortable' => false,
                ],
            ],
        ];

        return (new PaginateResource($permissions))
            ->additional($headers)
            ->collectionClass(PermissionResource::class);
    }

    /**
     * @param CreatePermissionRequest $request
     * @return PermissionResource
     */
    public function create(CreatePermissionRequest $request): PermissionResource
    {
        $dto = $request->toDTO();
        $permission = $this->roleService->createPermission($dto);

        return (new PermissionResource($permission))->additional(['message' => trans('roles.created')]);
    }

    /**
     * @param UpdatePermissionRequest $request
     * @return PermissionResource
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ServerErrorException
     */
    public function update(UpdatePermissionRequest $request): PermissionResource
    {
        $dto = $request->toDTO();
        $permission = $this->roleService->updatePermission($dto);

        return (new PermissionResource($permission))->additional(['message' => trans('roles.created')]);
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function permissionsFree(): AnonymousResourceCollection
    {
        $permissions = $this->roleService->findAvailablePermissions();

        return PermissionResource::collection($permissions)->additional(['accessor' => Accessor::all()->values()->all()]);
    }

    /**
     * @param int $permissionId
     * @return JsonResponse
     */
    public function delete(int $permissionId): JsonResponse
    {
        $this->roleService->permissionDelete($permissionId);

        return jsponse(['message' => 'Permission deleted', '_payload' => ['permissionId' => $permissionId]]);
    }
}
