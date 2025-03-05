<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Controller;

use App\Domain\CUM\Action\CreateCitizenPersonalAccessTokenAction;
use App\Domain\CUM\Http\Request\NidTokenRequest;
use App\Domain\CUM\Http\Resource\PersonalAccessResponse;
use Infrastructure\Http\Controllers\Controller;

final class LoginByUuidController extends Controller
{
    public function __invoke(
        NidTokenRequest $request,
        CreateCitizenPersonalAccessTokenAction $sendTokenAction,
    ): PersonalAccessResponse
    {
        $dto = $request->toDTO();
        $bearer = $sendTokenAction->transactionalRun(
            uuid: $dto->uuid,
            switchingAccount: true,
            machineDevice: $dto->tokenMachine
        );

        return new PersonalAccessResponse($bearer);
    }
}
