<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\Controller;

use App\Domain\Producer\Http\Action\SendEmailAction;
use App\Domain\Producer\Http\Request\SendEmailRequest;
use App\Domain\Producer\Http\Resource\AcceptCodeResource;
use Infrastructure\Http\Controllers\Controller;

final class SendEmailController extends Controller
{
    /**
     * @param SendEmailRequest $request
     * @param SendEmailAction $action
     * @return AcceptCodeResource
     */
    public function __invoke(SendEmailRequest $request, SendEmailAction $action): AcceptCodeResource
    {
        $dto = $request->toDTO();
        $result = $action->transactionalRun($dto);

        return new AcceptCodeResource($result);
    }
}
