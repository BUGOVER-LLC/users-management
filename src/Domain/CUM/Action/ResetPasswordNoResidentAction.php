<?php

declare(strict_types=1);

namespace App\Domain\CUM\Action;

use App\Core\Abstract\AbstractAction;
use App\Domain\CUM\Http\DTO\ChangePasswordNoResidentDTO;
use App\Domain\CUM\Model\Citizen;
use App\Domain\CUM\Repository\CitizenRepository;
use App\Domain\CUM\Service\ResetCodePasswordsService;
use Illuminate\Support\Facades\Hash;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Service\Repository\Exceptions\RepositoryException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method null|array transactionalRun(ChangePasswordNoResidentDTO $dto)
 */
class ResetPasswordNoResidentAction extends AbstractAction
{
    public function __construct(
        private readonly ResetCodePasswordsService $resetCodePasswordsService,
        private readonly CitizenRepository $citizenRepository
    )
    {
    }

    /**
     * @param $dto
     * @return array
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     */
    public function run($dto): array
    {
        $check = $this->checkCode($dto->code);
        if (!$check) {
            return [
                'message' => trans('auth.code_is_expire'),
                'code' => 422
            ];
        }

        $noResident = $this->updatePassword($check->email, $dto->password);

        if (!$noResident) {
            return [
                'data' => ['message' => trans('auth.resident_not_found'), '_payload' => ''],
                'code' => Response::HTTP_NOT_FOUND,
            ];
        }

        return [
            'data' => ['message' => trans('auth.reset_password_success'), '_payload' => ''],
            'code' => Response::HTTP_OK,
        ];
    }

    private function checkCode($code)
    {
        return $this->resetCodePasswordsService->checkResetCodePassword($code);
    }

    /**
     * @param $email
     * @param $password
     * @return Citizen|null
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     */
    private function updatePassword($email, $password): ?Citizen
    {
        $noResident = $this->citizenRepository->findByEmailWithProfile($email);

        if ($noResident) {
            return $this->citizenRepository->update(
                $noResident->citizenId,
                [
                    'password' => Hash::make($password),
                ],
                true
            );
        }
    }
}
