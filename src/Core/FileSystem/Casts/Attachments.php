<?php

declare(strict_types=1);

namespace App\Core\FileSystem\Casts;

use App\Core\FileSystem\FileSystem;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

final class Attachments implements CastsAttributes
{
    /**
     * Returns uploaded file(s)
     *
     * @throws \JsonException
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?array
    {
        if (!isset($attributes[$key])) {
            return null;
        }

        return json_decode($attributes[$key], true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Automatically upload file(s) while saving record.
     *
     * @throws \JsonException
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?array
    {
        if (!$value) {
            return null;
        }

        $fileSystem = new FileSystem();

        return [$key => json_encode($fileSystem->upload($value), JSON_THROW_ON_ERROR)];
    }
}
