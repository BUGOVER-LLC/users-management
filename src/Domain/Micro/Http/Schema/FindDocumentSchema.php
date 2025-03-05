<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Schema;

use App\Core\Abstract\AbstractSchema;
use App\Core\Enum\DocumentType;

class FindDocumentSchema extends AbstractSchema
{
    public function __construct(
        public readonly DocumentType $documentType,
        public readonly string $serialNumber,
        public readonly string $citizenship,
        public readonly string $dateIssue,
        public readonly null|string $dateExpiry,
        public readonly null|array $photo = [],
    ) {}
}
