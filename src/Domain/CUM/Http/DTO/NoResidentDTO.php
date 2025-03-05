<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\DTO;

use App\Core\Abstract\AbstractDTO;
use App\Core\Enum\DocumentType;
use App\Core\Enum\Gender;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Infrastructure\Http\Schemas\FileSchema;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(schema: NoResidentDTO::class, title: 'NoResidentDTO', properties: [
    new Property(
        property: 'firstName',
        type: 'string',
    ),
    new Property(
        property: 'lastName',
        type: 'string',
    ),
    new Property(
        property: 'patronymic',
        type: 'string',
    ),
    new Property(
        property: 'email',
        type: 'string',
    ),
    new Property(
        property: 'phone',
        type: 'string',
    ),
    new Property(
        property: 'password',
        type: 'string',
    ),
    new Property(
        property: 'passwordConfirmation',
        type: 'string',
    ),
    new Property(
        property: 'notificationAddress',
        type: 'string',
    ),
    new Property(
        property: 'dateBirth',
        type: 'string',
    ),
    new Property(
        property: 'registrationAddress',
        type: 'string',
    ),
    new Property(
        property: 'residenceAddress',
        type: 'string',
    ),
    new Property(
        property: 'gender',
        enum: [
            Gender::MALE->value,
            Gender::FEMALE->value,
        ],
    ),
    new Property(
        property: 'documentType',
        type: 'array',
        items: new Items(ref: FileSchema::class)
    ),
    new Property(
        property: 'documentFile',
        type: 'array',
        items: new Items(ref: FileSchema::class)
    ),
    new Property(
        property: 'documentNumber',
        type: 'string',
    ),
], type: 'object')]
class NoResidentDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $patronymic,
        public readonly string $email,
        public readonly string $phone,
        public readonly string $password,
        public readonly string $passwordConfirmation,
        public readonly string $notificationAddress,
        public readonly string|Carbon $dateBirth,
        public readonly string $registrationAddress,
        public readonly string $residenceAddress,
        public readonly string|Gender $gender,
        public readonly string|DocumentType $documentType,
        public readonly string $documentNumber,
        public readonly ?UploadedFile $documentFile,
    )
    {
    }
}
