<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Controller;

use App\Domain\Micro\Http\Request\FindDocumentRequest;
use App\Domain\Micro\Http\Response\FindDocumentResponse;
use App\Domain\UMAC\Repository\DocumentRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Infrastructure\Http\Controllers\Controller;

final class FindDocumentController extends Controller
{
    public function __invoke(
        FindDocumentRequest $request,
        DocumentRepository $documentRepository,
    ): AnonymousResourceCollection
    {
        $dto = $request->toDTO();
        $document = $documentRepository->findByTypeOrOwner($dto->ownerUuid, $dto->type);

        return FindDocumentResponse::collection(
            resource: $document,
        );
    }
}
