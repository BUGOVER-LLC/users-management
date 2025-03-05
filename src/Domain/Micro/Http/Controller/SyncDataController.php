<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Controller;

use App\Domain\Micro\Http\Action\SyncSystemDataAction;
use App\Domain\Micro\Http\Request\SyncDataRequest;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Controllers\Controller;

final class SyncDataController extends Controller
{
    public function __invoke(SyncDataRequest $request, SyncSystemDataAction $action): JsonResponse
    {
        $dto = $request->toDTO();
        $action->transactionalRun($dto);

        return jsponse(['message' => 'success']);
    }
}
