<?php

declare(strict_types=1);

namespace App\Domain\CUM\Action;

use App\Core\Abstract\AbstractAction;
use App\Core\Factories\HeaderFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Infrastructure\Exceptions\ServerErrorException;
use Symfony\Component\HttpFoundation\Response;

class FindAddressAction extends AbstractAction
{
    public function __construct(
        protected readonly Client $client,
    )
    {
    }

    /**
     * @throws ServerErrorException
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function run(string $uuid): mixed
    {
        $response = $this->client->get(config('app.service_api_url') . '/micro/address/' . $uuid, [
            'headers' => HeaderFactory::defaults(),
        ]);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new ServerErrorException($response->getBody()->getContents());
        }

        $address = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        Log::error('DECODED ADDRESS: ', [$address]);

        return $address['_payload'];
    }
}
