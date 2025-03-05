<?php

declare(strict_types=1);

namespace App\Core\FileSystem\Request;

use App\Core\FileSystem\Enum\FileCategoryEnum;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Attachment rules.
 */
trait HasAttachmentRules
{
    /**
     * Convert a potentially human-friendly file size to kilobytes.
     *
     * @param string|int $size
     * @return float|int
     */
    protected function toKilobytes($size): float|int
    {
        if (!\is_string($size)) {
            return $size;
        }

        $value = (float) $size;

        return round(
            match (true) {
                Str::endsWith($size, 'kb') => $value * 1,
                Str::endsWith($size, 'mb') => $value * 1000,
                Str::endsWith($size, 'gb') => $value * 1000000,
                Str::endsWith($size, 'tb') => $value * 1000000000,
                default => throw new InvalidArgumentException('Invalid file size suffix.'),
            }
        );
    }

    /**
     * Append attachment rules to base rules.
     *
     * @param string $fieldName
     * @param array $rules
     * @param FileCategoryEnum $category
     * @param array $mimeTypes
     * @param int|string $maxSize
     * @return array
     */
    public function attachmentRules(
        string $fieldName = 'attachments',
        array $rules = [],
        FileCategoryEnum $category = FileCategoryEnum::FILE,
        array $mimeTypes = [],
        int|string $maxSize = 0
    ): array
    {
        $requiredWith = "required_with:$fieldName.*.name";

        [
            'defaultMimeTypes' => $defaultMimeTypes,
            'defaultMaxSize' => $defaultMaxSize,
        ] = $this->getConfig($category->value);

        return [
            $fieldName => [
                'array',
                ...$rules,
            ],
            "$fieldName.*" => [
                'required',
                'array',
            ],
            "$fieldName.*.file" => [
                "required_without:$fieldName.*.name",
                'file',
                'mimes:' . implode(',', count($mimeTypes) ? $mimeTypes : $defaultMimeTypes),
                'max:' . $this->toKilobytes($maxSize ?: $defaultMaxSize),
            ],
            "$fieldName.*.name" => [
                'sometimes',
                'required',
                'string',
            ],
            "$fieldName.*.path" => [
                $requiredWith,
                'string',
            ],
            "$fieldName.*.size" => [
                $requiredWith,
                'int',
            ],
            "$fieldName.*.fileName" => [
                $requiredWith,
                'string',
            ],
            "$fieldName.*.mimeType" => [
                $requiredWith,
                'string',
            ],
            "$fieldName.*.uploadedAt" => [
                $requiredWith,
                'string',
            ],
        ];
    }

    /**
     * @param $category
     * @return array
     */
    private function getConfig($category): array
    {
        return [
            'defaultMimeTypes' => config("filesystems.{$category}_available_mime_types"),
            'defaultMaxSize' => config("filesystems.{$category}_max_size"),
        ];
    }
}
