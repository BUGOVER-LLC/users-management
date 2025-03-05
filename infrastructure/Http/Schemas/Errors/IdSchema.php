<?php

declare(strict_types=1);

namespace Infrastructure\Http\Schemas\Errors;

use App\Core\Abstract\AbstractSchema;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[
    Schema(schema: IdSchema::class, type: 'object')
]
final class IdSchema extends AbstractSchema
{
    #[Property()]
    public function __construct(
        public readonly int $id,
    )
    {
        //
    }
}
