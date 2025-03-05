<?php

declare(strict_types=1);

namespace Infrastructure\Http\Schemas;

use App\Core\Abstract\AbstractSchema;
use App\Core\Enum\Gender;
use Carbon\Carbon;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[
    Schema(
        schema: ProfileSchema::class,
        title: 'profile',
        properties: [
            new Property(
                property: 'profileId',
                type: 'int',
                nullable: false,
            ),
            new Property(
                property: 'psn',
                type: 'string',
                nullable: false,
            ),
            new Property(
                property: 'firstName',
                type: 'string',
                nullable: false,
            ),
            new Property(
                property: 'lastName',
                type: 'string',
                nullable: false,
            ),
            new Property(
                property: 'patronymic',
                type: 'string',
                nullable: true,
            ),
            new Property(
                property: 'gender',
                type: 'string',
                enum: [
                    Gender::MALE->value,
                    Gender::FEMALE->value,
                ],
                nullable: true,
            ),
            new Property(
                property: 'dateBirth',
                type: 'string',
                nullable: true,
            ),
            new Property(
                property: 'createdAt',
                type: 'string',
                nullable: true,
            ),
        ],
        type: 'object',
    )
]
class ProfileSchema extends AbstractSchema
{
    public function __construct(
        public readonly int $profileId,
        public readonly null|int|string $psn,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string|null $patronymic,
        public readonly Gender|null $gender,
        public readonly string|Carbon $dateBirth,
        public readonly null|string|Carbon $createdAt,
    )
    {
    }
}
