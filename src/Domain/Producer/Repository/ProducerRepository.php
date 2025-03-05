<?php

declare(strict_types=1);

namespace App\Domain\Producer\Repository;

use App\Domain\Producer\Model\Producer;
use Exception;
use Illuminate\Contracts\Container\Container;
use Infrastructure\Exceptions\ServerErrorException;
use Service\Repository\Repositories\EloquentRepository;

class ProducerRepository extends EloquentRepository
{
    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Producer::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('Producers');
    }

    /**
     * @param string $email
     * @return Producer|null
     */
    public function findByEmail(string $email): ?Producer
    {
        return $this->where('email', '=', $email)->findFirst();
    }

    /**
     * @param string $email
     * @return Producer|null
     */
    public function findByEmailHasPassword(string $email): ?Producer
    {
        return $this
            ->where('email', '=', $email)
            ->where('password', '!=')
            ->findFirst();
    }

    /**
     * @param string $email
     * @param string $password
     * @return Producer
     * @throws ServerErrorException
     */
    public function createProducerOnRegister(string $email, string $password): Producer
    {
        try {
            $producer = $this->create([
                'email' => $email,
                'password' => $password,
                'verifiedAt' => now(),
            ]);
        } catch (Exception $exception) {
            logging($exception);

            throw new ServerErrorException();
        }

        return $producer;
    }

    /**
     * @param int $producerId
     * @return bool
     */
    public function hasEnvironment(int $producerId): bool
    {
        return $this
            ->where('producerId', '=', $producerId)
            ->where('currentSystemId', '!=')
            ->exists();
    }
}
