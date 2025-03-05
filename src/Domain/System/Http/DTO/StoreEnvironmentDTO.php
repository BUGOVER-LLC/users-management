<?php

declare(strict_types=1);

namespace App\Domain\System\Http\DTO;

use App\Core\Abstract\AbstractDTO;
use Illuminate\Http\UploadedFile;

class StoreEnvironmentDTO extends AbstractDTO
{
    public function __construct(
        public readonly ?int $systemId,
        public readonly ?string $systemName,
        public readonly ?string $systemDomain,
        public readonly null|array|string|UploadedFile $systemLogo,
    )
    {
    }
}
