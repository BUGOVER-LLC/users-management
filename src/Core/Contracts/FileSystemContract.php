<?php

declare(strict_types=1);

namespace App\Core\Contracts;

use App\Core\FileSystem\FileSystem;
use DateTimeInterface;
use Illuminate\Contracts\Filesystem\Filesystem as LaravelFilesystem;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface FileSystemContract
{
    /**
     * @param string $bucket
     * @return FileSystem
     */
    public function setBucket(string $bucket): FileSystem;

    /**
     * @return string
     */
    public function getBucket(): string;

    /**
     * @param string $folder
     * @return FileSystem
     */
    public function setFolder(string $folder): FileSystem;

    /**
     * @return string|null
     */
    public function getFolder(): null|string;

    /**
     * @return FileSystem
     */
    public function signedDocuments(): FileSystem;

    /**
     * @param UploadedFile|array|string $file
     * @return array|null
     */
    public function upload(UploadedFile|array|string $file): ?array;

    /**
     * @param array|string $paths
     * @return bool
     */
    public function delete(array|string $paths): bool;

    /**
     * @param string $path
     * @return string
     */
    public function url(string $path): string;

    /**
     * @param string $path
     * @return string|null
     */
    public function get(string $path): null|string;

    /**
     * @param string $path
     * @param bool $temporaryUrl
     * @param DateTimeInterface|null $expiration
     * @return StreamedResponse|string
     */
    public function download(
        string $path,
        bool $temporaryUrl = false,
        DateTimeInterface $expiration = null
    ): StreamedResponse|string;

    /**
     * @param string $path
     * @return int
     */
    public function getSize(string $path): int;

    /**
     * @param UploadedFile $file
     * @return array|null
     */
    public function saveInBucket(UploadedFile $file): ?array;

    /**
     * @param string $base64data
     * @return array|null
     */
    public function saveBase64File(string $base64data): array|null;

    /**
     * @param UploadedFile $file
     * @return array
     */
    public function getUploadedFileInfo(UploadedFile $file): array;

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function hashName(UploadedFile $file): string;

    /**
     * @return LaravelFilesystem
     */
    public function storage(): LaravelFilesystem;
}
