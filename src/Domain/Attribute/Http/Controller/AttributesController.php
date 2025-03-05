<?php

declare(strict_types=1);

namespace App\Domain\Attribute\Http\Controller;

use App\Domain\Attribute\Http\Resource\AttributeSelectResource;
use App\Domain\Attribute\Http\Schema\AttributeSelectSchema;
use App\Domain\UMRA\Repository\AttributeRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Infrastructure\Http\Controllers\Controller;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AttributesController extends Controller
{
    public function __construct(
        protected readonly AttributeRepository $attributeRepository
    )
    {
    }

    #[Route(path: '/{resource}/attributes', methods: ['GET'])]
    #[
        Get(
            path: '/{resource}/attributes',
            description: 'Get attributes by given resource',
            summary: 'AttributeSelectSchema',
            tags: ['Attribute'],
        ),
        Response(
            response: 200,
            description: 'Attributes response',
            content: new JsonContent(
                properties: [
                    new Property(
                        property: '_payload',
                        type: AttributeSelectSchema::class
                    )
                ]
            )
        )
    ]
    public function __invoke($resource): AnonymousResourceCollection
    {
        $courtCases = $this->attributeRepository->findAllByResoure($resource);

        return AttributeSelectResource::collection($courtCases);
    }
}
