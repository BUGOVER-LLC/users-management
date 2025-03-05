<?php

declare(strict_types=1);

namespace Infrastructure\Http\Schemas\Errors;

use App\Core\Abstract\AbstractSchema;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[
    Schema(schema: ErrorMessageSchema::class, type: 'object')
]
final class ErrorMessageSchema extends AbstractSchema
{
    #[Property(property: 'message', type: 'string')]
    public function __construct(
        public readonly string $messsage,
    )
    {
        //
    }
}
