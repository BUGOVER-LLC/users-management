<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\DTO;

use App\Core\Abstract\AbstractDTO;
use App\Core\Enum\DocumentType;

class FindDocumentDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $ownerUuid,
        public readonly null|DocumentType $type = null,
    ) {}
}
