<?php

declare(strict_types=1);

namespace App\Domain\Oauth\Http\Controller;

use App\Domain\Oauth\Http\Request\CreateTokenClientRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use Infrastructure\Http\Controllers\Controller;
use Infrastructure\Http\Resource\Oauth\CreateClientResource;
use InvalidArgumentException;
use Laravel\Passport\Http\Controllers\ClientController;
use Symfony\Component\HttpFoundation\Response;

final class CreatePasswordTokenClientController extends Controller
{
    /**
     * @param CreateTokenClientRequest $request
     * @param ClientController $clientController
     * @return CreateClientResource
     */
    public function __invoke(
        CreateTokenClientRequest $request,
        ClientController $clientController
    ): CreateClientResource
    {
        try {
            $resource = $clientController->store($request);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            throw new InvalidArgumentException('Your data is invalid', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new CreateClientResource($resource);
    }
}
