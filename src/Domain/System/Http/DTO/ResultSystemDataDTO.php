<?php

declare(strict_types=1);

namespace App\Domain\System\Http\DTO;

use App\Core\Abstract\AbstractDTO;
use App\Domain\Oauth\Model\Client;
use App\Domain\System\Model\System;
use Illuminate\Support\Collection;

/**
 *
 */
class ResultSystemDataDTO extends AbstractDTO
{
    public function __construct(
        public readonly System $system,
        public readonly Collection|Client $clients,
    )
    {
    }
}
