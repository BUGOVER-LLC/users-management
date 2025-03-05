<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\Action;

use App\Core\Abstract\AbstractAction;
use App\Domain\Producer\Http\DTO\CheckSendCodeDTO;
use App\Domain\Producer\Repository\ProducerRepository;
use Illuminate\Validation\UnauthorizedException;
use Infrastructure\Illuminate\Redis\RedisRepository;

/**
 * @method transactionalRun(CheckSendCodeDTO $dto)
 */
final class CheckCodeAction extends AbstractAction
{
    /**
     * @param ProducerRepository $producerRepository
     * @param RedisRepository $redisRepository
     */
    public function __construct(
        private readonly ProducerRepository $producerRepository,
        protected readonly RedisRepository  $redisRepository
    )
    {
    }

    /**
     * @param CheckSendCodeDTO $dto
     * @return bool
     */
    public function run(CheckSendCodeDTO $dto): bool
    {
        $sent_code = $dto->authenticator ? $this->redisRepository->producerGetSendCode($dto->authenticator) : [];

        if (!$sent_code) {
            $equal_all_data = false;
        } elseif (!$sent_code['auth'] || !$sent_code['code'] || !$sent_code['email']) {
            $equal_all_data = false;
        } else {
            $equal_all_data = $sent_code['email'] === $dto->email
                && 0 === strcmp($sent_code['code'], $dto->code)
                && 0 === strcmp($sent_code['auth'], $dto->authenticator);
        }

        if (!$equal_all_data) {
            throw new UnauthorizedException('Unauthorized user data');
        }

        return !$this->producerRepository->findByEmailHasPassword($dto->email);
    }
}
