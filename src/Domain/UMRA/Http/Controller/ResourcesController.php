<?php

declare(strict_types=1);

namespace App\Domain\UMRA\Http\Controller;

use App\Domain\UMRA\Http\Request\EditResourcesRequest;
use App\Domain\UMRA\Http\Request\StoreResourcesRequest;
use App\Domain\UMRA\Repository\ResourceRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Infrastructure\Http\Controllers\Controller;
use Infrastructure\Http\Resource\ResourcesResource;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Service\Repository\Exceptions\RepositoryException;
use Symfony\Component\Config\Definition\Exception\Exception;

final class ResourcesController extends Controller
{
    public function __construct(
        private readonly ResourceRepository $resourceRepository
    )
    {
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function getResources(Request $request): AnonymousResourceCollection
    {
        $resource = $this->resourceRepository->findAllBySystemId($request->user()->currentSystemId);

        return ResourcesResource::collection($resource)->additional(['message' => 'success']);
    }

    /**
     * @param StoreResourcesRequest $request
     * @return ResourcesResource
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     * @throws \JsonException
     */
    public function createResource(StoreResourcesRequest $request): ResourcesResource
    {
        $dto = $request->toDTO();
        $resource = $this->resourceRepository->storeResource($dto);

        return (new ResourcesResource($resource))->additional(['message' => 'success']);
    }

    /**
     * @param EditResourcesRequest $request
     * @return ResourcesResource
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     * @throws \JsonException
     */
    public function editResource(EditResourcesRequest $request): ResourcesResource
    {
        $dto = $request->toDTO();
        $resource = $this->resourceRepository->storeResource($dto);

        return (new ResourcesResource($resource))->additional(['message' => 'success']);
    }

    /**
     * @param int $resourceId
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     * @throws \JsonException
     */
    public function deleteResource(int $resourceId): JsonResponse
    {
        try {
            $this->resourceRepository->delete($resourceId);
        } catch (Exception $exception) {
            logging($exception);
        }

        return jsponse(['message' => 'deleted']);
    }
}
