<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Controller;

use App\Domain\Micro\Http\Action\LogoutAction;
use App\Domain\Micro\Http\Request\LogoutRequest;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Controllers\Controller;

final class LogoutController extends Controller
{
    /**
     * @param LogoutRequest $request
     * @param LogoutAction $action
     * @return JsonResponse
     */
    public function __invoke(LogoutRequest $request, LogoutAction $action): JsonResponse
    {
        $dto = $request->toDTO();
        $device = $action->transactionalRun($dto);

        return jsponse([
            'message' => 'success',
            '_payload' => [
                'deviceName' => $device?->device,
            ],
        ]);
    }
}
