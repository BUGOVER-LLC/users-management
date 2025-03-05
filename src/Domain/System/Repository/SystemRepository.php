<?php

declare(strict_types=1);

namespace App\Domain\System\Repository;

use App\Domain\System\Model\System;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Service\Repository\Repositories\EloquentRepository;

class SystemRepository extends EloquentRepository
{
    public function __construct(Container $container)
    {
        $this
            ->setContainer($container)
            ->setCacheDriver('redis')
            ->setRepositoryId(System::getTableName())
            ->setModel(System::class);
    }

    /**
     * @param int $producerId
     * @return Collection<System>
     */
    public function findAllByProducerId(int $producerId): Collection
    {
        return $this->where('producerId', '=', $producerId)->findAll();
    }

    public function findByToken(string $token)
    {
        $claims = (new \Lcobucci\JWT\Token\Parser(new JoseEncoder()))
            ->parse($token)
            ->claims();

        return $this
            ->whereHas(
                'clients',
                fn(Builder $qb) => $qb
                    ->whereHas(
                        'tokens',
                        fn($qb) => $qb->where('id', '=', $claims->get('jti'))
                    )
            )
            ->findFirst();
    }
}
