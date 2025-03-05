<?php

declare(strict_types=1);

namespace App\Domain\Micro\Listeners;

use App\Domain\Oauth\Model\RefreshToken;
use App\Domain\System\Model\System;
use App\Domain\System\Repository\SystemRepository;
use App\Domain\UMAC\Model\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;

class UserTokenRevokeListener implements ShouldQueue
{
    public function __construct(
        private readonly Client $client,
        private readonly SystemRepository $systemRepository
    )
    {
    }

    public function handle(User $user, int $systemId): void
    {
        $accessTokens = $user
            ->tokens()
            ->whereHas(
                'client.system',
                fn(Builder $qb) => $qb->where('user_id', '=', $systemId)
            );

        (new RefreshToken())->whereIn('access_token_id', $accessTokens->get()->pluck('id'))->delete();
        $accessTokens->delete();

        /* @var System $system */
        $system = $this->systemRepository->find($systemId);

        if (!$system) {
            return;
        }

//        "https://$system->systemDomain/micro/tokens/$user->uuid" @TODO THIS IS REAL GET ENDPOINT
        $endpoint = config('app.service_api_url') . "/micro/tokens/$user->uuid";

        try {
            $this->client->delete($endpoint);
        } catch (Exception | GuzzleException $exception) {
            logging($exception);
        }
    }
}
