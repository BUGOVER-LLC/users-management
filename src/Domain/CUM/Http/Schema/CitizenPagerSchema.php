<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Schema;

use App\Core\Abstract\AbstractSchema;
use Infrastructure\Http\Schemas\AddressSchema;
use Infrastructure\Http\Schemas\CitizenSchema;
use Infrastructure\Http\Schemas\ProfileSchema;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[
    Schema(
        schema: CitizenPagerSchema::class,
        title: 'CitizenPagerSchema',
        allOf: [
            new Property(property: 'citizen', type: CitizenSchema::class),
            new Property(property: 'profile', type: ProfileSchema::class),
            new Property(property: 'address', type: AddressSchema::class),
        ]
    )
]
class CitizenPagerSchema extends AbstractSchema
{
    public function __construct(
        public readonly CitizenSchema|AbstractSchema $user,
        public readonly ProfileSchema|AbstractSchema $profile,
        public readonly AddressSchema|AbstractSchema $address,
    )
    {
    }
}
