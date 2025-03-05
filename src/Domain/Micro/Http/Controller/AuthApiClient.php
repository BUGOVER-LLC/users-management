<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Controller;

use App\Domain\Micro\Http\Request\AuthApiClientRequest;
use App\Domain\Oauth\Action\CreateGrantTokenAction;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Controllers\Controller;

final class AuthApiClient extends Controller
{
    public function __invoke(AuthApiClientRequest $request, CreateGrantTokenAction $action): JsonResponse
    {
        $dto = $request->toDTO();
        $result = $action->transactionalRun($dto->secret, $dto->domain);

        return jsponse($result);
    }
}
