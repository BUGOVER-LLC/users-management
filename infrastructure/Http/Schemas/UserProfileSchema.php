<?php

declare(strict_types=1);

namespace Infrastructure\Http\Schemas;

use App\Core\Abstract\AbstractSchema;
use App\Core\Enum\DocumentType;
use App\Core\Enum\Gender;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: UserProfileSchema::class,
    title: 'UserProfileSchema',
    properties: [
        new Property(
            property: 'userId',
            type: 'integer',
            nullable: false,
        ),
        new Property(
            property: 'psn',
            type: 'string',
            maximum: 10,
            minimum: 10,
            nullable: false,
        ),
        new Property(
            property: 'email',
            type: 'string',
            nullable: true,
        ),
        new Property(
            property: 'documentType',
            type: 'string',
            enum: [
                DocumentType::ID_CARD->value,
                DocumentType::BIRTH_CERTIFICATE->value,
                DocumentType::NON_BIOMETRIC_PASSPORT->value,
                DocumentType::BIOMETRIC_PASSPORT->value,
            ],
            nullable: true,
        ),
        new Property(
            property: 'documentValue',
            type: 'string',
            nullable: true,
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
            nullable: false,
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
            property: 'roleName',
            type: 'string',
            nullable: true,
        ),
    ]
)
]
class UserProfileSchema extends AbstractSchema
{
    public function __construct(
        public readonly ?int $userId,
        public readonly ?string $email,
        public readonly null|string|int $psn,
        public readonly null|string $documentType,
        public readonly null|string $documentValue,
        public readonly ?string $firstName,
        public readonly ?string $lastName,
        public readonly ?string $patronymic,
        public readonly Gender $gender,
        public readonly ?string $roleName = null,
    )
    {
    }
}
