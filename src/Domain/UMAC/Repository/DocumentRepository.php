<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Repository;

use App\Core\Enum\DocumentType;
use App\Domain\UMAC\Model\Documents;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Service\Repository\Repositories\EloquentRepository;

class DocumentRepository extends EloquentRepository
{
    public function __construct(Container $container)
    {
        $this
            ->setContainer($container)
            ->setModel(Documents::class)
            ->setRepositoryId(Documents::getTableName())
            ->setCacheDriver('redis');
    }

    /**
     * @param string $serialNumber
     *
     * @return Documents|null
     */
    public function findBySerialNumber(string $serialNumber): ?Documents
    {
        return $this->where('serialNumber', '=', $serialNumber)->findFirst();
    }

    /**
     * @param string $ownerUuid
     * @param DocumentType|null $documentType
     * @return Collection
     */
    public function findByTypeOrOwner(
        string $ownerUuid,
        null|DocumentType $documentType = null,
    ): Collection
    {
        return $this
            ->whereHas(
                rel: 'citizen',
                callback: function ($query) use ($ownerUuid) {
                    $query->where('uuid', $ownerUuid);
                },
            )
            ->when(
                value: $documentType,
                callback: function (Builder $query) use ($documentType) {
                    $query->where('documentType', $documentType);
                },
            )
            ->findAll();
    }
}
