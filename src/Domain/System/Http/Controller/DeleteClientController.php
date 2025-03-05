<?php

declare(strict_types=1);

namespace App\Domain\System\Http\Controller;

use App\Domain\System\Service\QueryService;
use Infrastructure\Http\Controllers\Controller;

final class DeleteClientController extends Controller
{
    public function __invoke(QueryService $queryService, int $clientId, ?bool $revoke = null)
    {
        if ($revoke) {
            $queryService->revokeAuthClient($clientId);
        } else {
            $queryService->deleteAuthClient($clientId);
        }

        return jsponse(['message' => 'Delete success']);
    }
}
