<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\Controller;

use App\Domain\Producer\Http\Action\CheckCodeAction;
use App\Domain\Producer\Http\Request\CheckSendCodeRequest;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Controllers\Controller;

final class SendCodeController extends Controller
{
    /**
     * @param CheckSendCodeRequest $request
     * @param CheckCodeAction $action
     * @return JsonResponse
     */
    public function __invoke(CheckSendCodeRequest $request, CheckCodeAction $action): JsonResponse
    {
        $dto = $request->toDTO();
        $result = $action->transactionalRun($dto);

        return jsponse([
            'message' => 'success',
            '_payload' => [
                'email' => $dto->email,
                'passwordConfirm' => $result,
            ],
        ]);
    }
}
