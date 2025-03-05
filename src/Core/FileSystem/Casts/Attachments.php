<?php

declare(strict_types=1);

namespace App\Core\FileSystem\Casts;

use App\Core\FileSystem\Contracts\FileSystemContract;
use App\Core\FileSystem\FileSystem;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

final class Attachments implements CastsAttributes
{
    protected FileSystem $fileSystem;

    public function __construct()
    {
        $this->fileSystem = app(FileSystemContract::class);
    }

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
        if (null === $value || !count($value)) {
            return null;
        }

        // TODO Remove if/else statement after missing attributes fixed
        if ($model->exists) {
            $model = \get_class($model)::find($model->getKey());
            if (!empty($model->{$key})) {
                $flushedAttachments = array_merge(
                    $this->flushAttachments($value, json_encode($model->{$key}, JSON_THROW_ON_ERROR)),
                    $this->fileSystem->upload($value),
                );
            } else {
                $flushedAttachments = $this->fileSystem->upload($value);
            }
        } else {
            $flushedAttachments = array_merge(
                $this->flushAttachments($value),
                $this->fileSystem->upload($value),
            );
        }

        return [$key => json_encode($flushedAttachments, JSON_THROW_ON_ERROR)];
    }

    /**
     * Flush model attachments.
     *
     * @param string|null $attachments
     * @param mixed $value
     * @return array
     * @throws \JsonException
     */
    protected function flushAttachments(mixed $value, null|string $attachments = null): array
    {
        $existingFiles = collect($value)
            ->filter(fn($attachment) => !isset($attachment['file']))
            ->map(function ($attachment) {
                $attachment['size'] = (int) $attachment['size'];

                return $attachment;
            })
            ->values();

        if (null === $attachments && !$existingFiles->count()) {
            return [];
        }

        $decoded = collect($attachments ? json_decode($attachments, true, 512, JSON_THROW_ON_ERROR) : []);
        $filesShouldDelete = $decoded->diffAssocMultiple($existingFiles)->values();
        $filesShouldAdd = $existingFiles->diffAssocMultiple($decoded)->values();
        $filesShouldKeep = $existingFiles
            ->diffAssocMultiple($filesShouldDelete)
            ->diffAssocMultiple($filesShouldAdd);

        $deletableFileNames = $filesShouldDelete->pluck('name')->all();

        $this->fileSystem->delete($deletableFileNames);

        foreach ($filesShouldAdd as $fileObject) {
            $filesShouldKeep->push($this->fileSystem->copyFileObject($fileObject));
        }

        return $filesShouldKeep->values()->all();
    }
}
