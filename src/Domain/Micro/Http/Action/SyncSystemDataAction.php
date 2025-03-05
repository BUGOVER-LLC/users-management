<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Action;

use App\Core\Abstract\AbstractAction;
use App\Domain\Micro\Http\DTO\SyncDataDTO;
use App\Domain\Micro\Http\Request\SyncDataRequest;
use App\Domain\System\Model\System;
use App\Domain\System\Repository\SystemRepository;
use App\Domain\UMRA\Repository\AttributeRepository;
use App\Domain\UMRA\Repository\ResourceRepository;
use App\Domain\UMRA\Repository\RoomRepository;
use App\Domain\UMRP\Repository\PermissionRepository;
use App\Domain\UMRP\Repository\RoleRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Service\Repository\Exceptions\RepositoryException;

/**
 * @method transactionalRun(SyncDataDTO $dto)
 */
final class SyncSystemDataAction extends AbstractAction
{
    /**
     * @var System
     */
    private readonly System $system;

    /**
     * @param AttributeRepository $attributeRepository
     * @param ResourceRepository $resourceRepository
     * @param RoleRepository $roleRepository
     * @param PermissionRepository $permissionRepository
     * @param SystemRepository $systemRepository
     * @param RoomRepository $roomRepository
     */
    public function __construct(
        private readonly AttributeRepository $attributeRepository,
        private readonly ResourceRepository $resourceRepository,
        private readonly RoleRepository $roleRepository,
        private readonly PermissionRepository $permissionRepository,
        private readonly SystemRepository $systemRepository,
        private readonly RoomRepository $roomRepository
    ) {
    }

    /**
     * @param SyncDataDTO $dto
     * @return void
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     */
    public function run(SyncDataDTO $dto)
    {
        if (!request()?->bearerToken()) {
            throw new RuntimeException('Unauthorized System', 401);
        }

        $this->system = $this->systemRepository->findByToken(request()->bearerToken());

        switch ($dto->type) {
            case SyncDataRequest::TYPE_ATTRIBUTE:
                $this->attributesSync($dto);
                break;
            case SyncDataRequest::TYPE_RESOURCE:
                $this->resourcesSync($dto);
                break;
            case SyncDataRequest::TYPE_PERMISSION:
                $this->permissionsSync($dto);
                break;
            case SyncDataRequest::TYPE_ROLE:
                $this->rolesSync($dto);
                break;
            case SyncDataRequest::TYPE_ROOMS:
                $this->roomsSync($dto);
                break;
        }
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     * @throws ContainerExceptionInterface
     * @throws BindingResolutionException
     * @throws JsonException
     */
    private function attributesSync(SyncDataDTO $dto): void
    {
        foreach ($dto->params as $param) {
            $resource = $this->resourceRepository->findByValue($param['resourceValue']);

            $this->attributeRepository->updateOrCreate(
            // @formatter:off
                [
                    'attributeValue', '=', $param['attributeValue'],
                    'systemId', '=', $this->system->systemId,
                ],
                // @formatter:on
                [
                    'attributeValue' => $param['attributeValue'],
                    'attributeName' => $param['attributeName'],
                    'attributeDescription' => $param['attributeDescription'],
                    'resourceId' => $resource?->resourceId,
                    'systemId' => $this->system->systemId,
                ]
            );
        }
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     * @throws ContainerExceptionInterface
     * @throws BindingResolutionException
     * @throws JsonException
     */
    private function resourcesSync(SyncDataDTO $dto): void
    {
        foreach ($dto->params as $param) {
            $this->resourceRepository->updateOrCreate(
            // @formatter:off
                [
                    'systemId', '=', $this->system->systemId,
                    'resourceValue', '=', $param['resourceValue'],
                ],
                // @formatter:on
                [
                    'systemId' => $this->system->systemId,
                    'resourceValue' => $param['resourceValue'],
                    'resourceName' => $param['resourceName'],
                    'resourceDescription' => $param['resourceDescription'],
                ]
            );
        }
    }

    /**
     * @param SyncDataDTO $dto
     * @return void
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     */
    private function permissionsSync(SyncDataDTO $dto): void
    {
        foreach ($dto->params as $param) {
            $this->permissionRepository->updateOrCreate(
            // @formatter:off
                [
                    'systemId', '=', $this->system->systemId,
                    'permissionValue', '=', $param['permissionValue'],
                ],
                // @formatter:on
                [
                    'systemId' => $this->system->systemId,
                    'permissionValue' => $param['permissionValue'],
                    'permissionName' => $param['permissionName'],
                    'permissionDescription' => $param['permissionDescription'],
                ]
            );
        }
    }

    /**
     * @param SyncDataDTO $dto
     * @return void
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     */
    private function rolesSync(SyncDataDTO $dto): void
    {
        foreach ($dto->params as $param) {
            if ($param['permissionValues'] ?? false) {
                $permissionsAccess = [];
                foreach ($param['permissionValues'] as $permissionValue) {
                    $permission = $this->permissionRepository->where(
                        'permissionValue',
                        '=',
                        $permissionValue['value']
                    )->findFirst(['permissionId', 'permissionValue']);
                    $permissionsAccess[] = [
                        'permissionId' => $permission->permissionId,
                        'access' => implode(',', $permissionValue['access']),
                    ];
                }

                $data = [
                    'systemId' => $this->system->systemId,
                    'roleValue' => $param['roleValue'],
                    'roleDescription' => $param['roleDescription'],
                    'roleName' => $param['roleName'],
                    'permissions' => $permissionsAccess,
                ];
            } else {
                $data = [
                    'systemId' => $this->system->systemId,
                    'roleValue' => $param['roleValue'],
                    'roleDescription' => $param['roleDescription'],
                    'roleName' => $param['roleName'],
                ];
            }

            $this->roleRepository->updateOrCreate(
            // @formatter:off
                [
                    'roleValue', '=', $param['roleValue'],
                    'systemId', '=', $this->system->systemId,
                ],
                $data,
                true,
                // @formatter:on
            );
        }
    }

    /**
     * @param SyncDataDTO $dto
     * @return void
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     */
    private function roomsSync(SyncDataDTO $dto): void
    {
        foreach ($dto->params as $param) {
            $attribute = $param['attributeValue'] ? $this->attributeRepository->findByValue(
                $param['attributeValue']
            ) : '';

            $this->roomRepository->updateOrCreate(
            // @formatter:off
                [
                    'roomValue','=', $param['roomValue'],
                    'systemId', '=', $this->system->systemId,
                ],
                [
                    'roomValue' => $param['roomValue'],
                    'roomName' => $param['roomName'],
                    'roomDescription' => $param['roomDescription'],
                    'systemId' => $this->system->systemId,
                    'attributeId' => $attribute?->attributeId ?? null,
                ]
                // @formatter:on
            );
        }
    }
}
