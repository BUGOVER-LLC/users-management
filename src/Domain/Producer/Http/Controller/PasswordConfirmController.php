<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\Controller;

use App\Domain\Producer\Http\Request\PasswordConfirmRequest;
use App\Domain\Producer\Service\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use Infrastructure\Exceptions\ServerErrorException;
use Infrastructure\Http\Controllers\Controller;
use Infrastructure\Illuminate\Redis\RedisRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RedisException;

final class PasswordConfirmController extends Controller
{
    /**
     * @param RedisRepository $redisRepository
     */
    public function __construct(
        private readonly RedisRepository $redisRepository
    )
    {
    }

    /**
     * @throws ServerErrorException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface|RedisException
     */
    public function __invoke(PasswordConfirmRequest $request, AuthService $authService): RedirectResponse
    {
        $dto = $request->toDTO();
        $authService->authorizeProducer($dto->email, $dto->password, $dto->repeatPassword);

        Cookie::forget('authenticator');
        $this->redisRepository->deleteProducerPasswordConfirm($request->cookie('authenticator'));

        return redirect()->route('producerBoard.index');
    }
}
