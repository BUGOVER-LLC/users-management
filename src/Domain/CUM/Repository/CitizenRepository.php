<?php

declare(strict_types=1);

namespace App\Domain\CUM\Repository;

use App\Core\Enum\AccessDocumentType;
use App\Domain\CUM\Model\Citizen;
use App\Domain\UMAC\Model\Profile;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Service\Repository\Repositories\EloquentRepository;

class CitizenRepository extends EloquentRepository
{
    public function __construct(Container $container)
    {
        $this
            ->setContainer($container)
            ->setModel(Citizen::class)
            ->setRepositoryId(Citizen::getTableName())
            ->setCacheDriver('redis');
    }

    /**
     * @param string|int $uuid
     * @return Citizen|null
     */
    public function findByUuid(string|int $uuid): ?Citizen
    {
        return $this
            ->with('profile')
            ->where('uuid', '=', $uuid)
            ->findFirst();
    }

    /**
     * @param string $email
     * @param array $columns
     * @return ?Citizen<Profile>
     */
    public function findByEmail(string $email, array $columns = ['*']): ?Citizen
    {
        return $this
            ->where('email', '=', $email)
            ->findFirst($columns);
    }

    /**
     * @param string $email
     * @return Citizen|null
     */
    public function findByEmailWithProfile(string $email): ?Citizen
    {
        return $this
            ->where('email', '=', $email)
            ->with('profile')
            ->findFirst();
    }

    /**
     * @param int|string $documentValue
     * @param AccessDocumentType $documentType
     * @return Citizen|null
     */
    public function findByDocument(int|string $documentValue, AccessDocumentType $documentType): ?Citizen
    {
        return $this
            ->when(
                value: AccessDocumentType::document->value === $documentType->value,
                callback: fn(Builder $query) => $query
                    ->whereHas(
                        relation: 'documents',
                        callback: fn (Builder $query) => $query->where('serialNumber', '=', $documentValue),
                    )
                    ->orWhere('documentValue', '=', $documentValue)
            )
            ->when(
                value: AccessDocumentType::psn->value === $documentType->value,
                callback: fn (Builder $query) => $query
                    ->whereHas(
                        relation: 'profile',
                        callback: fn (Builder $query) => $query->where('psn', '=', $documentValue)
                    )
            )
            ->with('profile')
            ->findFirst();
    }
}
