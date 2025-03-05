<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\DTO;

use App\Core\Abstract\AbstractDTO;
use App\Core\Enum\AccessDocumentType;

class PsnDTO extends AbstractDTO
{
    public function __construct(
        public readonly string|int $documentValue,
        public readonly AccessDocumentType $documentType,
    ) {}
}
