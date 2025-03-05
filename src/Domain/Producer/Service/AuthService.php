<?php

declare(strict_types=1);

namespace App\Domain\Producer\Service;

use App\Core\Enum\AuthGuard;
use App\Domain\Producer\Model\Producer;
use App\Domain\Producer\Repository\ProducerRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Infrastructure\Exceptions\ServerErrorException;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Service\Repository\Exceptions\RepositoryException;

class AuthService
{
    /**
     * @param ProducerRepository $producerRepository
     */
    public function __construct(private readonly ProducerRepository $producerRepository)
    {
    }

    /**
     * @param string $email
     * @param string $password
     * @return Producer
     * @throws ServerErrorException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function authorizeProducer(string $email, string $password, ?string $passwordConfirm): Producer
    {
        $producer = $this->producerRepository->findByEmail($email);

        if (!$passwordConfirm && !Hash::check($password, $producer?->password)) {
            throw new InvalidArgumentException();
        }

        if ($producer) {
            try {
                $this->producerRepository->update($producer->producerId, ['currentSystemId' => null]);
            } catch (Exception $exception) {
                logging($exception);

                throw new ServerErrorException();
            }
        } else {
            try {
                $producer = $this->producerRepository->createProducerOnRegister($email, Hash::make($password));
            } catch (Exception $exception) {
                logging($exception);

                throw new RuntimeException('Error during user creating', 500);
            }
        }

        Auth::guard(AuthGuard::webProducer->value)->loginUsingId($producer->producerId, true);
        Auth::setUser($producer);
        Event::dispatch('auth.producer.authentication', [$producer->producerId]);

        return $producer;
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \JsonException
     * @throws RepositoryException
     */
    public function logoutProducer(): void
    {
        $this->producerRepository->update(Auth::user()->{Auth::user()->getKeyName()}, ['currentSystemId' => null]);

        Auth::guard(AuthGuard::webProducer->value)->logout();
    }
}
