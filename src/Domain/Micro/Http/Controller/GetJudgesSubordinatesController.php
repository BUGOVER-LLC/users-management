<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Controller;

use App\Domain\Micro\Http\Response\SubordinatesUserResponse;
use App\Domain\Micro\Service\QueryService;
use Infrastructure\Http\Controllers\Controller;

class GetJudgesSubordinatesController extends Controller
{
    public function __invoke(string $judgeUuid, QueryService $queryService)
    {
        $subordinates = $queryService->getJudgeSubordinates($judgeUuid);

        return SubordinatesUserResponse::collection($subordinates);
    }
}
