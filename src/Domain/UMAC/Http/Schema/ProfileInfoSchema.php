<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Http\Schema;

use App\Core\Abstract\AbstractSchema;
use App\Core\Enum\PersonType;
use Carbon\Carbon;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[
    Schema(schema: ProfileInfoSchema::class, title: 'profile', properties: [
        new Property(property: 'psn', type: 'string'),
        new Property(property: 'firstName', type: 'string'),
        new Property(property: 'lastName', type: 'string'),
        new Property(property: 'patronymic', type: 'integer', nullable: true),
        new Property(property: 'gender', type: 'string'),
        new Property(property: 'dateBirth', type: 'string'),
        new Property(property: 'created', type: 'string'),
        new Property(property: 'documents', type: 'object'),
    ], type: 'object')
]
class ProfileInfoSchema extends AbstractSchema
{
    public function __construct(
        public readonly null|int|string $psn,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string|null $patronymic,
        public readonly string|null $gender,
        public readonly string|Carbon $dateBirth,
        public readonly null|string|Carbon $created = null,
        public readonly null|array $documents = null,
        public readonly null|PersonType $personType = null,
        public readonly null|string $email = null,
    )
    {
    }
}

