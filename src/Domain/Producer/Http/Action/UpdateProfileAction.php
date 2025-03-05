<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\Action;

use App\Core\Abstract\AbstractAction;
use App\Domain\Producer\Http\DTO\ProfileDTO;
use App\Domain\Producer\Repository\ProducerRepository;
use Exception;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

final class UpdateProfileAction extends AbstractAction
{
    public function __construct(
        private readonly ProducerRepository $producerRepository,
    )
    {
    }

    public function run(ProfileDTO $dto)
    {
        $data = [
            'username' => $dto->username,
            'email' => $dto->email,
        ];

        if ($dto->newPassword) {
            $producer = $this->producerRepository->findByEmail($dto->email);

            if ($producer && !Hash::check($dto->password, $producer->password)) {
                throw new InvalidArgumentException();
            }

            $dto->newPassword
                ? $data['password'] = Hash::make($dto->newPassword)
                : '';
        }

        try {
            $this->producerRepository->update($dto->id, $data);
        } catch (Exception $exception) {
            logging($exception);

            throw new RuntimeException('server error', 500);
        }

        return $this->producerRepository->findByEmail($dto->email);
    }
}
