<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Controller;

use App\Domain\CUM\Action\CreateCitizenAction;
use App\Domain\CUM\Action\CreateCitizenPersonalAccessTokenAction;
use App\Domain\CUM\Action\CreateUserPersonalAccessTokenAction;
use App\Domain\CUM\Http\Request\CustomCallbackRequest;
use App\Domain\CUM\Http\Request\NidTokenRequest;
use App\Domain\CUM\Http\Request\UserTokenRequest;
use App\Domain\CUM\Http\Resource\PersonalAccessResponse;
use App\Domain\CUM\Http\Schema\BearerSchema;
use App\Domain\UMAC\Service\ProfileService;
use asd\NID\NIDIntegration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Event;
use Infrastructure\Http\Controllers\Controller;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NidController extends Controller
{
    /**
     * @param ProfileService $profileService
     * @param NIDIntegration $nidIntegration
     */
    public function __construct(
        private readonly ProfileService $profileService,
        private readonly NIDIntegration $nidIntegration,
    )
    {
    }

    /**
     * property
     *
     * @return JsonResponse
     */
    public function login(): JsonResponse
    {
        return jsponse(['url' => $this->nidIntegration->login()]);
    }

    #[Route(path: '/nid/login', methods: ['GET'])]
    #[
        Get(
            path: '/nid/login',
            description: 'User NID login',
            summary: 'BearerSchema',
            tags: ['Auth'],
            parameters: [
                new Parameter(name: 'state', in: 'query', required: true),
                new Parameter(name: 'code', in: 'query', required: true),
            ],
            responses: [
                new Response(
                    response: 200,
                    description: 'Get all categories ordered by ASC',
                    content: new JsonContent(properties: [
                        new Property(
                            property: '_payload',
                            type: BearerSchema::class
                        ),
                    ])
                ),
            ]
        )
    ]
    public function callback(
        CustomCallbackRequest $request,
        CreateCitizenAction $createCitizenAction,
    ): Redirector|RedirectResponse
    {
        if ('helloworld' === $request->state) {//TODO for local work
            $citizenInfo['profile']['given_name'] = 'ԱՐԹՈՒՐ';
            $citizenInfo['profile']['family_name'] = 'ՄԻՆԱՍՅԱՆ';
            $citizenInfo['profile']['psn'] = $request->code;
        } else {
            $citizenInfo = $this->nidIntegration->callback($request);
        }
// @TODO hhewfhf
        $profile = $createCitizenAction->transactionalRun($citizenInfo['profile']);

        return redirect(config('services.ekeng.redirect_url') . $profile->citizen->uuid);
    }

    public function token(
        NidTokenRequest $request,
        CreateCitizenPersonalAccessTokenAction $sendTokenAction,
    ): PersonalAccessResponse
    {
        $dto = $request->toDTO();

        $profile = $this->profileService->findCitizenProfileByUuid($dto->uuid);
        $bearer = $sendTokenAction->transactionalRun($dto->uuid);

        Event::dispatch(
            'citizen.created',
            [$bearer['accessToken'], $profile->citizen->citizenId]
        );

        return (new PersonalAccessResponse($bearer))
            ->additional([
                'firstSign' => !$profile?->citizen->password,
            ]);
    }


    public function userToken(
        UserTokenRequest $request,
        CreateUserPersonalAccessTokenAction $sendTokenAction,
    ): PersonalAccessResponse
    {
        $dto = $request->toDTO();
        $profile = $this->profileService->findUserProfileByUuid($dto->uuid);
        $bearer = $sendTokenAction->transactionalRun($dto->uuid);

        Event::dispatch(
            'user.created',
            [$bearer['accessToken'], $profile->user->userId]
        );

        return (new PersonalAccessResponse($bearer))
            ->additional([
                'firstSign' => !$profile?->user->passwordHash,
            ]);
    }
}
