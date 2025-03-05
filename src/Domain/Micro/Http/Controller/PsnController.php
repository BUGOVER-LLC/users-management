<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Controller;

use App\Domain\Micro\Http\Action\StoreCitizenAsContributorAction;
use App\Domain\Micro\Http\Action\StoreCitizenByPsnAction;
use App\Domain\Micro\Http\Request\PsnRequest;
use App\Domain\Micro\Http\Request\StoreCitizenByDocTypeRequest;
use App\Domain\Micro\Http\Request\StoreCitizenRequest;
use App\Domain\Micro\Http\Response\PsnInfoResource;
use App\Domain\Micro\Http\Response\StoreCitizenResponse;
use App\Domain\Micro\Service\QueryService;
use App\Domain\UMAC\Exception\IdentityDocumentDoesntExistsException;
use App\Domain\UMAC\Exception\UserPsnDoesntExistsException;
use App\Domain\UMAC\Model\Profile;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Infrastructure\Exceptions\ServerErrorException;
use Infrastructure\Http\Controllers\Controller;
use RedisException;

final class PsnController extends Controller
{
    public function __construct(private readonly QueryService $queryService)
    {
    }

    /**
     * @param PsnRequest $request
     * @return PsnInfoResource
     *
     * @throws GuzzleException
     * @throws RedisException
     * @throws ServerErrorException
     * @throws UserPsnDoesntExistsException
     * @throws IdentityDocumentDoesntExistsException
     */
    public function getUserInfoByPsn(PsnRequest $request): PsnInfoResource
    {
        $dto = $request->toDTO();

        $citizen = $this->queryService->getByDocumentType(
            $dto->documentValue,
            $dto->documentType,
        );

        return new PsnInfoResource($citizen);
    }

    /**
     * @param StoreCitizenRequest $request
     * @param StoreCitizenAsContributorAction $action
     * @return StoreCitizenResponse
     */
    public function storeCitizen(
        StoreCitizenRequest $request,
        StoreCitizenAsContributorAction $action
    ): StoreCitizenResponse
    {
        $dto = $request->toDTO();
        /* @var Profile $profile */
        $profile = $action->transactionalRun($dto);

        Event::dispatch(
            'citizen.created',
            [$request->bearerToken(), $profile->citizen->citizenId]
        );

        return new StoreCitizenResponse($profile);
    }

    /**
     * @param StoreCitizenByDocTypeRequest $request
     * @param StoreCitizenByPsnAction $action
     * @return JsonResponse
     */
    public function storeCitizenByDocType(
        StoreCitizenByDocTypeRequest $request,
        StoreCitizenByPsnAction $action
    ): JsonResponse
    {
        $dto = $request->toDTO();
        $result = $action->transactionalRun($dto);

        Event::dispatch(
            'citizen.created',
            [$request->bearerToken(), $result->citizen?->citizenId ?? $result->citizenId]
        );

        return jsponse(['message' => 'success']);
    }
}
