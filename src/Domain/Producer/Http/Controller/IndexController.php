<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\Controller;

use App\Core\Enum\ProducerAuthStep;
use App\Domain\Producer\Repository\ProducerRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Infrastructure\Http\Controllers\Controller;
use Infrastructure\Illuminate\Redis\RedisRepository;
use RedisException;

final class IndexController extends Controller
{
    /**
     * @param ProducerRepository $producerRepository
     * @param RedisRepository $redisRepository
     */
    public function __construct(
        private readonly ProducerRepository $producerRepository,
        private readonly RedisRepository $redisRepository
    )
    {
    }

    /**
     * @throws RedisException
     */
    public function __invoke(Request $request): Factory|View
    {
        $sent_code = $request->cookie('authenticator') ? $this->redisRepository->producerGetSendCode(
            $request->cookie('authenticator')
        ) : null;
        $step = ProducerAuthStep::email->value;

        if (!$sent_code) {
            $accept_code = false;
        } elseif (!$sent_code['auth'] || !$sent_code['code'] || !$sent_code['email']) {
            $accept_code = false;
        } else {
            $step = ProducerAuthStep::code->value;
            $accept_code = $sent_code['auth'] === $request->cookie('authenticator');
            $email = $sent_code['email'];
        }

        return view('producer.auth', [
            'code' => $accept_code,
            'email' => $email ?? '',
            'passwordConfirm' => isset($email) && !$this->producerRepository->findByEmailHasPassword($email),
            'step' => $step,
        ]);
    }
}
