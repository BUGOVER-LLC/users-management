<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Controller;

use App\Domain\CUM\Action\ProfileUpdateAction;
use App\Domain\CUM\Http\DTO\ProfileUpdateDTO;
use App\Domain\CUM\Http\Request\ProfileUpdateRequest;
use App\Domain\CUM\Http\Resource\CitizenResponse;
use App\Domain\CUM\Http\Resource\ProfileUpdateResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Controllers\Controller;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileUpdateController extends Controller
{
    #[
        Route(
            path: '/profile/update',
            name: 'profile.update',
            requirements: ['citizenId' => '\D+']
        )
    ]
    #[
        Put(
            path: '/profile/update',
            description: 'Returns updated citizen with profile information',
            summary: 'Updates a citizen profile',
            requestBody: new RequestBody(
                required: true,
                content: new MediaType(
                    mediaType: 'application/json',
                    schema: new Schema(
                        ref: ProfileUpdateDTO::class,
                    )
                ),
            ),
            tags: ['Edit profile'],
            responses: [
                new Response(
                    response: ResponseStatus::HTTP_OK,
                    description: 'Returns citizen and profile information',
                    content: new JsonContent(
                        properties: [
                            new Property(
                                property: '_payload',
                                type: CitizenResponse::class
                            )
                        ]
                    ),
                ),
            ],
        )
    ]
    public function __invoke(
        ProfileUpdateRequest $request,
        ProfileUpdateAction $action,
    ): ProfileUpdateResponse|JsonResponse
    {
        try {
            $action->transactionalRun($request->toDTO());
        } catch (Exception $exception) {
            logging($exception);

            return jsponse(
                [
                    'status' => ResponseStatus::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $exception->getMessage(),
                ],
                ResponseStatus::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return jsponse(
            [
                'status' => ResponseStatus::HTTP_OK,
                'message' => __('messages.profile_updated'),
            ],
            ResponseStatus::HTTP_OK
        );
    }
}
