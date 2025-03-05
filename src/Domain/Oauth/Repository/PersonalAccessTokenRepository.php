<?php

declare(strict_types=1);

namespace App\Domain\Oauth\Repository;

use App\Domain\Oauth\Model\PersonalClient;
use Illuminate\Contracts\Container\Container;
use Service\Repository\Repositories\EloquentRepository;

class PersonalAccessTokenRepository extends EloquentRepository
{
    public function __construct(Container $container)
    {
        $this->setContainer($container)->setModel(PersonalClient::class)->setRepositoryId(
            (new PersonalClient())->getTable()
        );
    }
}
