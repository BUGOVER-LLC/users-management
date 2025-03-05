<?php

declare(strict_types=1);

namespace App\Domain\System\Http\Controller;

use App\Domain\Oauth\Action\CreateGrantTokenAction;
use App\Domain\System\Http\Request\StoreEnvironmentRequest;
use App\Domain\System\Service\QueryService;
use Infrastructure\Http\Controllers\Controller;
use Infrastructure\Http\Resource\SystemResource;

final class StoreEnvironment extends Controller
{
    public function __invoke(
        StoreEnvironmentRequest $request,
        QueryService $queryService,
        CreateGrantTokenAction $action
    )
    {
        $dto = $request->toDTO();
        $system = $queryService->saveOrSetEnvironment($dto);
        $system->publicClient?->secret
            ? $action->transactionalRun($system->publicClient->secret, $system->systemDomain)
            : null;

        return (new SystemResource($system))->additional(['message' => 'success']);
    }
}
