<?php

declare(strict_types=1);

namespace App\Core\Api\Ekeng\DTO;

use App\Core\Abstract\AbstractDTO;

final class EkgPassportDTO extends AbstractDTO
{
    public function __construct(
        public readonly ?string $type,
        public readonly ?string $status,
        public readonly null|string|int $serialNumber,
        public readonly ?string $dateIssue,
        public readonly ?string $dateExpiry,
        public readonly ?string $authority,
        public readonly ?string $citizenship,
        public readonly ?string $photo = null,
        public readonly bool $isPrimary = false,
    )
    {
    }
}
