<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Action\Role;

use App\Core\Abstract\AbstractAction;
use App\Domain\UMAC\Model\User;
use App\Domain\UMAC\Repository\UserRepository;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * @method void transactionalRun(int $roleId)
 */
final class RoleUpdateSyncAction extends AbstractAction
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly Client $client
    )
    {
    }

    /**
     * @param int $roleId
     */
    public function run(int $roleId): void
    {
        $this->userRepository
            ->findAllByRoleId($roleId)
            ->map(
                fn(User $user) => $user->tokens()->get()->map(
                    fn($item) => [
                        usleep(500000),
                        $this->sendLogout($user->uuid),
                        $item->refreshToken->delete(),
                        $item->delete(),
                    ]
                )
            );
    }

    /**
     * @param string $uuid
     * @return void
     */
    private function sendLogout(string $uuid): void
    {
        try {
            $this->client->post(
                config('app.service_api_url') . '/micro/logout',
                [
                    'form_params' => ['uuid' => $uuid],
                    'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest'],
                ]
            );
        } catch (Exception | GuzzleException $exception) {
            logging($exception);
        }
    }
}
