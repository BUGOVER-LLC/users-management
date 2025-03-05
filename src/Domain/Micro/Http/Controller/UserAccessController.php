<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Controller;

use App\Domain\Micro\Exception\UserCannotHaveRoleException;
use App\Domain\Micro\Http\Response\UserAccessResponse;
use App\Domain\UMAC\Model\User;
use App\Domain\UMAC\Repository\ProfileRepository;
use Infrastructure\Http\Controllers\Controller;
use Infrastructure\Illuminate\Auth\Contracts\AuthorizedUser;

final class UserAccessController extends Controller
{
    public function __construct(
        protected readonly AuthorizedUser $authorizedUser,
        protected readonly ProfileRepository $profileRepository,
    )
    {
    }

    /**
     * @throws UserCannotHaveRoleException
     */
    public function __invoke(null|string $uuid = null): UserAccessResponse
    {
        if ($uuid) {
            $profile = $this->profileRepository->findCurrentProfileByUuid($uuid);

            if (!$profile && !$profile->user) {
                throw new UserCannotHaveRoleException();
            }

            $person = $profile->user;
        } else {
            $person = $this->authorizedUser->user();

            if (!$person instanceof User && !$person->user) {
                throw new UserCannotHaveRoleException();
            }
        }

        return new UserAccessResponse($person);
    }
}
