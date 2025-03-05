<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Controller;

use App\Domain\CUM\Action\PasswordChangeAction;
use App\Domain\CUM\Http\DTO\PasswordChangeDTO;
use App\Domain\CUM\Http\Request\PasswordChangeRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Controllers\Controller;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Schema;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PasswordChangeController extends Controller
{
    #[
        Route(
            path: '/profile/password/change',
            name: 'profile.password.change',
            requirements: ['userId' => '\D+']
        )
    ]
    #[
        Put(
            path: '/profile/password/change',
            description: 'Returns message after successful password change',
            summary: 'Update citizen password',
            requestBody: new RequestBody(
                required: true,
                content: new MediaType(
                    mediaType: 'application/json',
                    schema: new Schema(
                        ref: PasswordChangeDTO::class,
                    )
                ),
            ),
            tags: ['Edit profile'],
            parameters: [
                new Parameter(
                    name: 'userId',
                    description: 'User id',
                    in: 'query',
                    required: true,
                    schema: new Schema(
                        type: 'integer',
                    )
                ),
                new Parameter(
                    name: 'password',
                    description: 'Password',
                    in: 'query',
                    required: true,
                    schema: new Schema(
                        type: 'string',
                    )
                ),
                new Parameter(
                    name: 'passwordConfirmation',
                    description: 'Password repeat',
                    in: 'query',
                    required: true,
                    schema: new Schema(
                        type: 'string',
                    )
                ),
            ],
            responses: [
                new \OpenApi\Attributes\Response(
                    response: Response::HTTP_OK,
                    description: 'Returns message after successful password change',
                    content: new JsonContent(
                        properties: [
                            new Property(property: 'message', type: 'string'),
                        ],
                    ),
                ),
            ],
        )
    ]
    public function __invoke(PasswordChangeRequest $request, PasswordChangeAction $action): JsonResponse
    {
        try {
            $action->transactionalRun($request->toDTO());
        } catch (Exception $exception) {
            logging($exception);

            return jsponse(
                [
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $exception->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        return jsponse(
            [
                'status' => Response::HTTP_OK,
                'message' => 'Password changed successfully',
            ],
            Response::HTTP_OK,
        );
    }
}
