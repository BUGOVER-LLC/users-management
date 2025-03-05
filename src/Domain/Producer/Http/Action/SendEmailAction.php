<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\Action;

use App\Core\Abstract\AbstractAction;
use App\Core\Enum\EmailType;
use App\Core\Enum\ProducerAuthStep;
use App\Domain\Producer\Http\DTO\SendEmailDTO;
use App\Domain\Producer\Http\Schema\AcceptCodeSchema;
use App\Domain\Producer\Queue\SendMailQueue;
use Illuminate\Support\Str;
use Infrastructure\Illuminate\Redis\RedisRepository;
use RedisException;

final class SendEmailAction extends AbstractAction
{
    /**
     * @throws RedisException
     */
    public function __construct(private readonly RedisRepository $redisRepository)
    {
    }

    /**
     * @param SendEmailDTO $dto
     * @return AcceptCodeSchema
     * @throws RedisException
     */
    protected function run(SendEmailDTO $dto): AcceptCodeSchema
    {
        $accept_code = Str::upper(Str::random(6));

        $this->redisRepository->setProducerAuthData($dto->authenticator, $accept_code, $dto->email);

        SendMailQueue::dispatch(
            EmailType::acceptCode->value,
            $dto->email,
            ['accept_code' => $accept_code]
        );

        return new AcceptCodeSchema($dto->email, ProducerAuthStep::code->value);
    }
}
