<?php

declare(strict_types=1);

namespace Infrastructure\Http\Schemas;

use App\Core\Abstract\AbstractSchema;
use App\Core\Enum\DocumentType;
use App\Core\Enum\PersonType;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[
    Schema(
        schema: CitizenSchema::class,
        title: 'citizen',
        properties: [
            new Property(
                property: 'citizenId',
                type: 'integer',
                nullable: false,
            ),
            new Property(
                property: 'isActive',
                type: 'integer',
                nullable: false,
            ),
            new Property(
                property: 'isChecked',
                type: 'integer',
                nullable: false,
            ),
            new Property(
                property: 'personType',
                type: 'string',
                enum: [
                    PersonType::RESIDENT->value,
                    PersonType::NON_RESIDENT->value,
                    PersonType::UNDEFINED->value,
                ],
                nullable: false,
            ),
            new Property(
                property: 'email',
                type: 'string',
                nullable: true,
            ),
            new Property(
                property: 'phone',
                type: 'string',
                nullable: true,
            ),
            new Property(
                property: 'documentType',
                type: 'string',
                enum: [
                    DocumentType::NON_BIOMETRIC_PASSPORT->value,
                    DocumentType::BIRTH_CERTIFICATE->value,
                    DocumentType::ID_CARD->value,
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
                property: 'documentFile',
                type: 'string',
                nullable: true,
            ),
        ],
        type: 'object',
    )
]
class CitizenSchema extends AbstractSchema
{
    public function __construct(
        public readonly int $citizenId,
        public readonly bool $isActive,
        public readonly bool $isChecked,
        public readonly PersonType|null $personType,
        public readonly string|null $email,
        public readonly string|null $phone,
        public readonly DocumentType|null $documentType,
        public readonly string|null $documentValue,
        public readonly string|null $documentFile,
    )
    {
    }
}
