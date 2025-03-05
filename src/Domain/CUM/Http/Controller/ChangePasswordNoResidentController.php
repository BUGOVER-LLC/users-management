<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Controller;

use App\Domain\CUM\Action\ResetPasswordNoResidentAction;
use App\Domain\CUM\Http\Request\ChangePasswordNoResidentRequest;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Controllers\Controller;
use Infrastructure\Http\Schemas\Errors\ErrorMessageSchema;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

final class ChangePasswordNoResidentController extends Controller
{
    #[
        Put(
            path: '/password/change',
            description: 'Edit Citizen Information',
            summary: 'Change password',
            tags: ['Edit profile'],
            parameters: [
                new Parameter(
                    name: 'code',
                    description: 'code',
                    in: 'path',
                    required: true,
                    schema: new Schema(
                        type: 'string'
                    )
                ),
                new Parameter(
                    name: 'password',
                    description: 'password',
                    in: 'path',
                    required: true,
                    schema: new Schema(
                        type: 'string'
                    )
                ),
                new Parameter(
                    name: 'passwordConfirmation',
                    description: 'Password confirmation',
                    in: 'path',
                    required: true,
                    schema: new Schema(
                        type: 'string'
                    )
                ),
            ],
            responses: [
                new Response(
                    response: 200,
                    description: 'Success Get Citizen Information',
                    content: new JsonContent(
                        properties: [
                            new Property(
                                property: 'message',
                                type: 'string'
                            )
                        ]
                    ),
                ),
                new Response(
                    response: 404,
                    description: 'Not found',
                    content: new JsonContent(ref: ErrorMessageSchema::class),
                ),
            ]
        ),
    ]
    public function __invoke(
        ChangePasswordNoResidentRequest $changePasswordNoResidentRequest,
        ResetPasswordNoResidentAction $resetPasswordNoResidentAction
    ): JsonResponse
    {
        $dto = $changePasswordNoResidentRequest->toDTO();
        $result = $resetPasswordNoResidentAction->transactionalRun($dto);

        return jsponse($result['data'], $result['code']);
    }
}
