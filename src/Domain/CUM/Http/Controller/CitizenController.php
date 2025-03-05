<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Controller;

use App\Core\Enum\SwaggerTags;
use App\Domain\CUM\Action\EditCitizenAction;
use App\Domain\CUM\Action\SendBearerApiMicroAction;
use App\Domain\CUM\Http\DTO\CitizenLoginDTO;
use App\Domain\CUM\Http\DTO\EditCitizenDTO;
use App\Domain\CUM\Http\Request\CitizenLoginRequest;
use App\Domain\CUM\Http\Request\EditCitizenRequest;
use App\Domain\CUM\Http\Resource\BearerResponse;
use App\Domain\CUM\Http\Resource\CitizenResponse;
use App\Domain\Micro\Dispatch\SyncCreateCitizenApiTrigger;
use Exception;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Controllers\Controller;
use Infrastructure\Http\Resource\UserProfileResource;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Schema;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CitizenController extends Controller
{
    #[Route(path: '/me', methods: ['GET'])]
    #[
        Get(
            path: '/me',
            description: 'Get Auth Citizen Information',
            tags: [SwaggerTags::getData->value]
        ),
        \OpenApi\Attributes\Response(
            response: 200,
            description: 'Success Get Citizen Information',
            content: new JsonContent(
                properties: [
                    new Property(
                        property: '_payload',
                        type: CitizenResponse::class
                    )
                ]
            ),
        ),
    ]
    public function getCitizen(): UserProfileResource
    {
        $citizen = auth()->user();

        return (new UserProfileResource($citizen))->additional([
            'code' => 200,
        ]);
    }

    #[Route(path: 'complete-registration/{userId}', name: 'edit', requirements: ['userId' => '\D+'])]
    #[
        Put(
            path: '/complete-registration/{userId}',
            description: 'Edit Citizen Information',
            summary: 'UserProfileResource',
            requestBody: new RequestBody(
                required: true,
                content: new MediaType(
                    mediaType: 'application/json',
                    schema: new Schema(
                        ref: EditCitizenDTO::class
                    ),
                ),
            ),
            tags: ['Edit profile'],
            parameters: [
                new Parameter(
                    name: 'userId',
                    description: 'user Id',
                    in: 'path',
                    required: true,
                    schema: new Schema(
                        type: 'integer'
                    )
                ),
                new Parameter(
                    name: 'email',
                    description: 'Email',
                    in: 'path',
                    required: true,
                    schema: new Schema(
                        type: 'string'
                    )
                ),
                new Parameter(
                    name: 'phone',
                    description: 'Phone number',
                    in: 'path',
                    required: false,
                    schema: new Schema(
                        type: 'string'
                    )
                ),
                new Parameter(
                    name: 'password',
                    description: 'Password',
                    in: 'path',
                    required: false,
                    schema: new Schema(
                        type: 'string'
                    )
                ),
                new Parameter(
                    name: 'passwordConfirmation',
                    description: 'Password confirmation',
                    in: 'path',
                    required: false,
                    schema: new Schema(
                        type: 'string'
                    )
                ),
            ],
            responses: [
                new \OpenApi\Attributes\Response(
                    response: 200,
                    description: 'Success Get Citizen Information',
                    content: new JsonContent(
                        properties: [
                            new Property(
                                property: '_payload',
                                type: CitizenResponse::class
                            )
                        ]
                    ),
                ),
            ]
        ),
    ]
    public function completeRegistration(
        EditCitizenRequest $editCitizenRequest,
        EditCitizenAction $editCitizenAction,
        int $userId
    ): UserProfileResource|JsonResponse
    {
        $dto = $editCitizenRequest->toDTO();

        try {
            $profile = $editCitizenAction->transactionalRun($dto);
            SyncCreateCitizenApiTrigger::dispatchSync($profile->profileId);
        } catch (Exception $exception) {
            logging($exception);

            return jsponse(
                [
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'wrong',
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        return (new UserProfileResource($profile->citizen))
            ->additional([
                'status' => Response::HTTP_OK,
                'message' => trans('citizens.updated'),
            ]);
    }

    #[Route(path: 'login', name: 'login')]
    #[
        Post(
            path: '/login',
            description: 'Login Citizen',
            requestBody: new RequestBody(
                content: new JsonContent(type: CitizenLoginDTO::class),
            ),
            tags: ['Auth']
        ),
        \OpenApi\Attributes\Response(
            response: 200,
            description: 'Success Login',
            content: new JsonContent(
                properties: [
                    new Property(
                        property: '_payload',
                        type: BearerResponse::class
                    )
                ]
            ),
        )
    ]
    public function login(
        CitizenLoginRequest $citizenLoginRequest,
        SendBearerApiMicroAction $bearerToken,
    ): BearerResponse
    {
        $dto = $citizenLoginRequest->toDTO();
        $result = $bearerToken->run($dto);

        return new BearerResponse($result);
    }
}
