<?php

declare(strict_types=1);

namespace App\Domain\System\Http\Schema;

use App\Core\Abstract\AbstractSchema;
use Infrastructure\Http\Schemas\SystemSchema;

class GetSystemDataSchema extends AbstractSchema
{
    public function __construct(
        public readonly SystemSchema|AbstractSchema $system,
        public readonly array $clients,
        public readonly mixed $clientTypes,
        public readonly mixed $providerTypes,
    )
    {
    }
}
