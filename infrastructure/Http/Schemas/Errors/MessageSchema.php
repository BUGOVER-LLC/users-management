<?php

declare(strict_types=1);

namespace Infrastructure\Http\Schemas\Errors;

use App\Core\Abstract\AbstractSchema;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[
    Schema(schema: MessageSchema::class, type: 'object')
]
final class MessageSchema extends AbstractSchema
{
    #[Property()]
    public function __construct(
        public string $message,
    ) {
        //
    }
}
