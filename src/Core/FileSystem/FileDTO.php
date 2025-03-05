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

    #[
        ArrayShape([
            'name' => 'string',
            'file_name' => 'string',
            'mime_type' => 'string',
            'size' => 'string',
            'uploadedAt' => 'string',
        ])
    ]
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'file_name' => $this->originalName,
            'mime_type' => $this->mime,
            'size' => $this->size,
            'uploadedAt' => $this->uploadedAt ?? now()->toDateTimeString(),
        ];
    }
}
