<?php

declare(strict_types=1);

namespace App\Domain\System\Http\Controller;

use App\Domain\System\Http\Request\StoreSystemDataRequest;
use App\Domain\System\Service\QueryService;
use Infrastructure\Http\Controllers\Controller;
use Infrastructure\Http\Resource\ClientResource;

final class StoreSystemData extends Controller
{
    /**
     * @param StoreSystemDataRequest $request
     * @param QueryService $queryService
     * @return ClientResource
     */
    public function __invoke(StoreSystemDataRequest $request, QueryService $queryService): ClientResource
    {
        $dto = $request->toDTO();
        $client = $queryService->createClient($dto);

        return (new ClientResource($client))->additional(['message' => 'Success created']);
    }
}
