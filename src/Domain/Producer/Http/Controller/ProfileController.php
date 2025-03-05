<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\Controller;

use App\Domain\Producer\Http\Action\UpdateProfileAction;
use App\Domain\Producer\Http\Request\ProfileUpdateRequest;
use App\Domain\Producer\Http\Resource\ProfileResource;
use App\Domain\Producer\Service\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Controllers\Controller;
use Infrastructure\Http\Resource\ProducerResource;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Service\Repository\Exceptions\RepositoryException;

final class ProfileController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    )
    {
    }

    /**
     * @return ProducerResource
     */
    public function profile(): ProducerResource
    {
        return new ProducerResource(Auth::user());
    }

    /**
     * @param ProfileUpdateRequest $request
     * @param UpdateProfileAction $profileAction
     * @return ProfileResource
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     */
    public function update(ProfileUpdateRequest $request, UpdateProfileAction $profileAction): ProfileResource
    {
        $dto = $request->toDTO();
        $profile = $profileAction->transactionalRun($dto);

        $result = (new ProfileResource($profile))
            ->additional(['message' => trans('roles.created')]);

        $this->logout();

        return $result;
    }

    /**
     * @return RedirectResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws JsonException
     * @throws RepositoryException
     */
    public function logout(): RedirectResponse
    {
        $this->authService->logoutProducer();

        return redirect()->route('authProducer.index');
    }
}
