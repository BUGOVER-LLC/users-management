<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Controller;

use App\Domain\CUM\Action\NoResidentSignUpAction;
use App\Domain\CUM\Http\DTO\NoResidentDTO;
use App\Domain\CUM\Http\Request\InviteAcceptRequest;
use App\Domain\CUM\Http\Request\NoResidentSignUpRequest;
use App\Domain\CUM\Http\Schema\CitizenPagerSchema;
use Infrastructure\Http\Controllers\Controller;
use Infrastructure\Http\Resource\UserProfileResource;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NoResidentController extends Controller
{
    #[Route(path: 'sign-up', name: 'signUp', methods: ['POST'])]
    #[
        Post(
            path: '/sign-up',
            description: 'No resident registration',
            requestBody: new RequestBody(
                content: new JsonContent(type: NoResidentDTO::class),
            ),
            tags: ['Auth'],
            responses: [
                new Response(
                    response: 200,
                    description: 'Success Gets Citizen Information',
                    content: new JsonContent(
                        properties: [
                            new Property(
                                property: '_payload',
                                type: CitizenPagerSchema::class
                            ),
                        ]
                    )
                ),
            ]
        ),
    ]
    /**
     * @param NoResidentSignUpRequest $noResidentSignUpRequest
     * @param NoResidentSignUpAction $noResidentSignUpAction
     * @return UserProfileResource
     */
    public function signUp(
        NoResidentSignUpRequest $noResidentSignUpRequest,
        NoResidentSignUpAction $noResidentSignUpAction
    ): UserProfileResource
    {
        $dto = $noResidentSignUpRequest->toDTO();
        $citizen = $noResidentSignUpAction->transactionalRun($dto);

        return (new UserProfileResource($citizen))->additional([
            'message' => trans('citizens.account_check'),
        ]);
    }

    #[Route(path: 'invite/accept', name: 'inviteAccept', methods: ['GET'])]
    #[
        Get(
            path: '/invite/accept',
            description: 'Accept Invite',
            summary: 'Invite',
            tags: ['Auth'],
            responses: [
                new Response(
                    response: 200,
                    description: 'Invite Accepted (but not finished, need accept auth microservice)',
                    content: new JsonContent(
                        properties: [
                            new Property(
                                property: 'message',
                                type: 'String'
                            ),
                        ]
                    ),
                ),
            ]
        ),
    ]
    /**
     * @param InviteAcceptRequest $inviteAcceptRequest
     * @return void
     */
    public function inviteAccept(InviteAcceptRequest $inviteAcceptRequest)
    {
        $dto = $inviteAcceptRequest->toDTO();
    }
}
