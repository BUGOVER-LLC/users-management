<?php

declare(strict_types=1);

namespace App\Domain\UMRA\Http\Controller;

use App\Domain\UMRA\Http\Request\EditAttributeRequest;
use App\Domain\UMRA\Http\Request\StoreAttributeRequest;
use App\Domain\UMRA\Repository\AttributeRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Infrastructure\Http\Controllers\Controller;
use Infrastructure\Http\Resource\AttributeResource;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Service\Repository\Exceptions\RepositoryException;
use Symfony\Component\Config\Definition\Exception\Exception;

final class AttributeController extends Controller
{
    use AttributesHeader;

    public function __construct(
        private readonly AttributeRepository $attributeRepository,
    )
    {
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function getAttributes(Request $request): AnonymousResourceCollection
    {
        $resource = $this->attributeRepository->findAllBySystemId($request->user()->currentSystemId);

        return AttributeResource::collection($resource)
            ->additional([
                'message' => 'success',
                'headers' => $this->getDataHeadersAttributes(),
            ]);
    }

    /**
     * @param StoreAttributeRequest $request
     * @return AttributeResource
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     */
    public function storeAttribute(StoreAttributeRequest $request): AttributeResource
    {
        $dto = $request->toDTO();
        $resource = $this->attributeRepository->storeAttribute($dto);

        return (new AttributeResource($resource))->additional(['message' => 'success']);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     * @throws ContainerExceptionInterface
     * @throws BindingResolutionException
     * @throws JsonException
     */
    public function editAttribute(EditAttributeRequest $request): AttributeResource
    {
        $dto = $request->toDTO();
        $resource = $this->attributeRepository->storeAttribute($dto);

        return (new AttributeResource($resource))->additional(['message' => 'success']);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     * @throws ContainerExceptionInterface
     * @throws BindingResolutionException
     * @throws JsonException
     */
    public function deleteAttribute(int $attributeId): JsonResponse
    {
        try {
            $this->attributeRepository->delete($attributeId);
        } catch (Exception $exception) {
            logging($exception);
        }

        return jsponse(['message' => 'deleted']);
    }
}
