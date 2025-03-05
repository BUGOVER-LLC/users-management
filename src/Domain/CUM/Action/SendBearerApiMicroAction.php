<?php

declare(strict_types=1);

namespace App\Domain\CUM\Action;

use App\Core\Abstract\AbstractAction;
use App\Core\Enum\AuthProvider;
use App\Domain\CUM\Http\DTO\CitizenLoginDTO;
use App\Domain\CUM\Repository\CitizenRepository;
use App\Domain\Oauth\Manager\ServerManager as OauthServerManager;
use App\Domain\UMAC\Model\Profile;
use App\Domain\UMAC\Model\User;
use App\Domain\UMAC\Repository\ProfileRepository;
use App\Domain\UMAC\Repository\UserRepository;
use Illuminate\Support\Facades\Hash;
use Infrastructure\Exceptions\HttpException;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Service\Repository\Exceptions\RepositoryException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method transactionalRun(CitizenLoginDTO $dto)
 */
final class SendBearerApiMicroAction extends AbstractAction
{
    public function __construct(
        protected CitizenRepository $citizenRepository,
        protected UserRepository $userRepository,
        protected ProfileRepository $profileRepository,
    )
    {
    }

    /**
     * @param CitizenLoginDTO $dto
     * @return array
     * @throws ContainerExceptionInterface
     * @throws HttpException
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     */
    public function run(CitizenLoginDTO $dto): array
    {
        // Get profile with user & citizen.
        $profile = $this->profileRepository->findCurrentProfileByEmail($dto->email);

        if (!$profile) {
            throw new HttpException(__('auth.failed'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $switchedAccount = false;

        // Check if user and citizen has same email and password.
        if ($this->validateIsSameAccount($profile, $dto->password)) { // @TODO BUG WHEN NONE USER
            // If user and citizen has same email and password
            // then check if user or citizen has latest authenticated account.
            if ($profile->currentLogin) {
                $switchedAccount = true;
                $user = $profile->currentLogin;
            } else {
                $user = $profile->user;
            }
        } else {
            $user = $this->userRepository->findByEmail($dto->email);

            if (!$user) {
                $user = $this->citizenRepository->findByEmail($dto->email);
            }
        }

        if (!$user->password || !Hash::check($dto->password, $user->password)) {
            throw new HttpException(__('auth.failed'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->profileRepository->update(
            $user->profileId,
            [
                'currentLoginType' => $user::map(),
                'currentLoginId' => $user->getKey(),
            ],
        );

        $provider = $user instanceof User ? AuthProvider::users->value : AuthProvider::citizens->value;

        return OauthServerManager::createBearerByEmailPWDWithScope(
            uuid: $user->uuid,
            email: $dto->email,
            password: $dto->password,
            provider: $provider,
            switchedAccount: $switchedAccount,
        );
    }

    /**
     * @param Profile $profile
     * @param string $password
     * @return bool
     */
    private function validateIsSameAccount(Profile $profile, string $password): bool
    {
        return $profile->user && $profile->citizen
            && $profile->user->email === $profile->citizen->email
            && $profile->user->password && Hash::check($password, $profile->user->password)
            && $profile->citizen->password && Hash::check($password, $profile->citizen->password);
    }
}
