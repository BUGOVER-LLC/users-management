<?php

declare(strict_types=1);

namespace App\Domain\UMAA\Http\Controller;

use App\Core\Enum\AuthProvider;
use App\Domain\Oauth\Http\Response\BearerResponse;
use App\Domain\Oauth\Http\Schema\BearerSchema;
use App\Domain\Oauth\Manager\ServerManager as OauthServerManager;
use App\Domain\UMAA\Action\InviteToUserAction;
use App\Domain\UMAA\Action\SendDataToApiAction;
use App\Domain\UMAA\Http\Request\AcceptInviteUserRequest;
use Exception;
use Illuminate\Support\Facades\Event;
use Infrastructure\Exceptions\ServerErrorException;
use Infrastructure\Http\Controllers\Controller;
use JetBrains\PhpStorm\NoReturn;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AcceptInviteUser extends Controller
{
    /**
     * @param AcceptInviteUserRequest $request
     * @param InviteToUserAction $inviteToUserAction
     * @param SendDataToApiAction $dataToApiAction
     * @return BearerResponse
     * @throws ServerErrorException
     */
    #[Route(path: 'invite/accept', methods: ['POST'])]
    #[
        Post(
            path: '/umaa/invite/accept',
            description: 'Accept invite',
            summary: 'Invite',
            tags: ['Auth'],
            parameters: [
                new Parameter(name: 'username', in: 'path', required: true),
            ]
        ),
        Response(
            response: 200,
            description: 'Get all categories ordered by ASC',
            content: new JsonContent(
                properties: [
                    new Property(
                        property: '_payload',
                        type: BearerSchema::class
                    ),
                ]
            ),
        )
    ]
    #[NoReturn] public function __invoke(
        AcceptInviteUserRequest $request,
        InviteToUserAction $inviteToUserAction,
        SendDataToApiAction $dataToApiAction,
    ): BearerResponse
    {
        $dto = $request->toDTO();

        try {
            $user = $inviteToUserAction->transactionalRun($dto);
            $bearer = OauthServerManager::createBearerByEmailPWDWithScope(
                uuid: $user->uuid,
                email: $user->email,
                password: $dto->password,
                provider: AuthProvider::users->value,
            );
            $bearer = $dataToApiAction->transactionalRun($bearer, $user);
        } catch (Exception $exception) {
            logging($exception);

            throw new ServerErrorException();
        }

        Event::dispatch('user.created', [$bearer['accessToken'], $user->userId]);
        Event::dispatch('user.login', $user);

        return new BearerResponse($bearer);
    }
}
