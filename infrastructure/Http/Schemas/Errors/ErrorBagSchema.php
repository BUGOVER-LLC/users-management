<?php

declare(strict_types=1);

namespace Infrastructure\Http\Schemas\Errors;

use App\Core\Abstract\AbstractSchema;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[
    Schema(schema: ErrorBagSchema::class, type: 'object')
]
final class ErrorBagSchema extends AbstractSchema
{
    #[Property(property: 'errors', type: 'object')]
    public function __construct(
        public readonly array $errors,
    )
    {
        //
    }
}
