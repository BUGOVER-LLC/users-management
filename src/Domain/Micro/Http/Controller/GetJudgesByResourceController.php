<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Controller;

use App\Domain\Micro\Service\QueryService;
use Infrastructure\Http\Controllers\Controller;
use Infrastructure\Http\Resource\UserResource;

final class GetJudgesByResourceController extends Controller
{
    public function __invoke(string $resourceValue, QueryService $queryService)
    {
        $users = $queryService->getJudgesByResource($resourceValue);

        return UserResource::collection($users);
    }
}
