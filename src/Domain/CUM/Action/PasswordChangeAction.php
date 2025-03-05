<?php

declare(strict_types=1);

namespace App\Domain\CUM\Action;

use App\Core\Abstract\AbstractAction;
use App\Domain\CUM\Http\DTO\PasswordChangeDTO;
use App\Domain\CUM\Model\Citizen;
use App\Domain\CUM\Repository\CitizenRepository;
use Illuminate\Support\Facades\Hash;

/**
 * @method null|Citizen transactionalRun(PasswordChangeDTO $dto)
 */
final class PasswordChangeAction extends AbstractAction
{
    public function __construct(
        protected readonly CitizenRepository $citizenRepository,
    )
    {
    }

    public function run(PasswordChangeDTO $dto)
    {
        return $this->citizenRepository->update(
            $dto->citizenId,
            [
                'password' => Hash::make($dto->password),
            ],
        );
    }
}
