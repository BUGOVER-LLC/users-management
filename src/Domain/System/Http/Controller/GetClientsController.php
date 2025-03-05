<?php

declare(strict_types=1);

namespace App\Domain\System\Http\Controller;

use App\Domain\System\Http\DTO\ResultSystemDataDTO;
use App\Domain\System\Http\Response\GetSystemDataResponse;
use App\Domain\System\Service\QueryService;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Controllers\Controller;

final class GetClientsController extends Controller
{
    /**
     * @param QueryService $queryService
     * @return GetSystemDataResponse
     */
    public function __invoke(QueryService $queryService): GetSystemDataResponse
    {
        $clients = $queryService->findAllClientByUserId(Auth::user()->currentSystemId);
        $system = $queryService->findSystemById(Auth::user()->currentSystemId);
        $result = new ResultSystemDataDTO($system, $clients);

        return new GetSystemDataResponse($result);
    }
}
