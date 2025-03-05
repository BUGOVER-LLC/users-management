<?php

namespace App\Domain\CUM\Repository;

use App\Domain\CUM\Model\ResetCodePasswords;
use Illuminate\Container\Container;
use Service\Repository\Repositories\EloquentRepository;

class ResetCodePasswordsRepository extends EloquentRepository
{
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(ResetCodePasswords::class)
            ->setRepositoryId(ResetCodePasswords::getTableName());
    }



}
