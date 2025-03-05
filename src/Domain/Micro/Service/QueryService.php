<?php

declare(strict_types=1);

namespace App\Domain\Micro\Service;

use App\Core\Api\Ekeng\DTO\EkgDTO;
use App\Core\Api\Ekeng\EkengApi;
use App\Core\Enum\AccessDocumentType;
use App\Domain\CUM\Model\Citizen;
use App\Domain\CUM\Repository\CitizenRepository;
use App\Domain\UMAC\Exception\IdentityDocumentDoesntExistsException;
use App\Domain\UMAC\Exception\UserPsnDoesntExistsException;
use App\Domain\UMAC\Model\Profile;
use App\Domain\UMAC\Model\User;
use App\Domain\UMAC\Repository\UserRepository;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Infrastructure\Exceptions\ServerErrorException;
use Infrastructure\Http\Schemas\ProfileSchema;
use Infrastructure\Illuminate\Redis\RedisRepository;
use RedisException;

readonly class QueryService
{
    /**
     * @param CitizenRepository $citizenRepository
     * @param EkengApi $ekengApi
     * @param RedisRepository $redisRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        private CitizenRepository $citizenRepository,
        private EkengApi $ekengApi,
        private RedisRepository $redisRepository,
        private UserRepository $userRepository
    )
    {
    }

    /**
     * @param int|string $documentValue
     * @param AccessDocumentType $documentType
     * @return Citizen<Profile>|EkgDTO
     * @throws GuzzleException
     * @throws IdentityDocumentDoesntExistsException
     * @throws RedisException
     * @throws ServerErrorException
     * @throws UserPsnDoesntExistsException
     * @throws \JsonException
     */
    public function getByDocumentType(int|string $documentValue, AccessDocumentType $documentType): Citizen|EkgDTO
    {
        $citizen = $this->redisRepository->findCitizenByPsn($documentValue);

        if (!$citizen) {
            $citizen = $this->citizenRepository->findByDocument($documentValue, $documentType);
        }

        if (!$citizen) {
            $citizen = $this->ekengApi->send($documentValue, $documentType)->getPersonInfo();

            try {
                $this->redisRepository->saveCitizenByPsn($citizen);
            } catch (Exception $exception) {
                logging($exception);
            }
        }

        /* @var Citizen|ProfileSchema $citizen */
        return $citizen;
    }

    /**
     * @param string $attributeValue
     * @param string $roleValue
     * @return Collection<User>
     */
    public function getUsersByRoleInAttribute(string $attributeValue, string $roleValue): Collection
    {
        return $this->userRepository
            ->whereHas(
                'role',
                fn(Builder $qb) => $qb->where('roleValue', '=', $roleValue)
            )->where(fn(Builder $qb) => $qb->whereHas(
                'attribute',
                fn(Builder $qb) => $qb->where('attributeValue', '=', $attributeValue)
            )->orWhereHas(
                'parent',
                fn(Builder $qb) => $qb->whereHas(
                    'attribute',
                    fn($qb) => $qb->where('attributeValue', '=', $attributeValue)
                )
            ))
            ->findAll();
    }

    /**
     * @param string $uuid
     * @return ?User
     */
    public function getUserAttributeValue(string $uuid): ?User
    {
        return $this->userRepository->findByUuid($uuid)->attribute->attributeValue;
    }

    /**
     * @param string $resourceValue
     * @return Collection<User>
     */
    public function getJudgesByResource(string $resourceValue): Collection
    {
        return $this->userRepository
            ->whereHas(
                'role',
                fn(Builder $qb) => $qb->where('roleValue', '=', 'judge')
            )
            ->whereHas(
                'attribute',
                fn(Builder $qb) => $qb->whereHas(
                    'resource',
                    fn(Builder $qb) => $qb->where('resourceValue', '=', $resourceValue)
                )
            )
            ->findAll();
    }

    /**
     * @param $judgeUuid
     * @return Collection<User>
     */
    public function getJudgeSubordinates($judgeUuid): Collection
    {
        return $this->userRepository->findALlUserChildsByParentUuid($judgeUuid);
    }
}
