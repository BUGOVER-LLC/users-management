<?php

declare(strict_types=1);

namespace App\Core\FileSystem;

use Illuminate\Contracts\Support\Arrayable;
use JetBrains\PhpStorm\ArrayShape;

readonly class FileDTO implements Arrayable
{
    public function __construct(
        public string $name,
        public string $originalName,
        public string $mime,
        public int $size,
        public null|string $uploadedAt = null,
    )
    {
    }

    /**
     * @return array{name: string, fileName: string, mimeType: string, size: string, uploadetAt: string}
     */
    #[
        ArrayShape([
            'name' => 'string',
            'fileName' => 'string',
            'mimeType' => 'string',
            'size' => 'string',
            'uploadedAt' => 'string',
        ])
    ]
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'fileName' => $this->originalName,
            'mimeType' => $this->mime,
            'size' => $this->size,
            'uploadedAt' => $this->uploadedAt ?? now()->toDateTimeString(),
        ];
    }
}
