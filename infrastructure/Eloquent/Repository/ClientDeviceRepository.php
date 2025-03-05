<?php

declare(strict_types=1);

namespace Infrastructure\Eloquent\Repository;

use App\Core\Utils\MachineId;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Infrastructure\Eloquent\Model\ClientDevice;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Service\Repository\Exceptions\RepositoryException;
use Service\Repository\Repositories\EloquentRepository;

class ClientDeviceRepository extends EloquentRepository
{
    public function __construct(Container $container)
    {
        $this
            ->setContainer($container)
            ->setModel(ClientDevice::class)
            ->setCacheDriver('redis')
            ->setRepositoryId(ClientDevice::getTableName());
    }

    /**
     * @param int|string $clientId
     * @param string $clientType
     * @param string|null $device
     * @return Model|object|null
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     */
    public function persistDevice(int|string $clientId, string $clientType, ?string $device = null)
    {
        $instance = MachineId::instance();
        $deviceName = $device ?: $instance->getDeviceName();

        return $this->updateOrCreate(
            ['clientId', '=', $clientId, 'clientType', '=', $clientType, 'device', '=', $deviceName],
            [
                'clientId' => $clientId,
                'clientType' => $clientType,
                'device' => $deviceName,
                'ip' => $instance->getIpAddress(),
                'loggedAt' => now(),
                'logoutAt' => null,
            ]
        );
    }

    /**
     * @param int $clientId
     * @param string $clientType
     * @return ?ClientDevice
     */
    public function findLatestDevice(int $clientId, string $clientType): ?ClientDevice
    {
        return $this
            ->where('clientId', '=', $clientId)
            ->where('clientType', '=', $clientType)
            ->firstLatest('loggedAt');
    }
}
