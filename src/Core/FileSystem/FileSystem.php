<?php

declare(strict_types=1);

namespace App\Core\FileSystem;

use App\Core\Contracts\FileSystemContract;
use DateTimeInterface;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem as LaravelFilesystem;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\File\Exception\CannotWriteFileException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

use function is_array;
use function is_string;

final class FileSystem implements FileSystemContract
{
    protected string $bucket;

    protected null|string $folder = null;

    public function __construct()
    {
        $this->bucket = 's3';
    }

    public function setBucket(string $bucket): FileSystem
    {
        $this->bucket = $bucket;

        return $this;
    }

    public function getBucket(): string
    {
        return $this->bucket;
    }

    public function setFolder(string $folder): FileSystem
    {
        $this->folder = $folder;

        return $this;
    }

    public function getFolder(): null|string
    {
        $folder = $this->folder;

        if (null === $folder) {
            return $folder;
        }

        if (str_ends_with($folder, '/')) {
            return $folder;
        }

        return "$folder/";
    }

    public function signedDocuments(): FileSystem
    {
        $this->bucket = 's3signed';

        return $this;
    }

    /**
     * Upload single or multiple files to bucket.
     *
     * @param UploadedFile|array|string $file
     *
     * @return array|null
     *
     * @throws FileNotFoundException|CannotWriteFileException
     */
    #[
        ArrayShape([
            'name' => 'string',
            'file_name' => 'string',
            'mime_type' => 'string',
            'size' => 'string',
            'uploaded_at' => 'string',
        ])
    ]
    public function upload(UploadedFile|array|string $file): ?array
    {
        if (is_array($file)) {
            $uploadedFileInfo = [];

            foreach ($file as $uploadedFile) {
                if ($uploadedFile instanceof UploadedFile) {
                    $uploadedFileInfo[] = $this->saveInBucket($uploadedFile);
                }

                if (is_string($uploadedFile)) {
                    $uploadedFileInfo[] = $this->saveBase64File($uploadedFile);
                }
            }

            return $uploadedFileInfo;
        }

        if (is_string($file)) {
            return $this->saveBase64File($file);
        }

        return $this->saveInBucket($file);
    }

    public function delete(array|string $paths): bool
    {
        return $this->storage()->delete($paths);
    }

    public function url(string $path): string
    {
        return $this->storage()->url($path);
    }

    public function get(string $path): null|string
    {
        return $this->storage()->get($path);
    }

    /**
     * Download file from bucket or create temporary url for given path.
     *
     * @param string $path
     * @param bool $temporaryUrl
     * @param DateTimeInterface|null $expiration
     *
     * @return string|StreamedResponse
     *
     * @throws HttpException
     */
    public function download(
        string $path,
        bool $temporaryUrl = false,
        DateTimeInterface $expiration = null
    ): StreamedResponse|string
    {
        $storage = $this->storage();

        abort_if(!$storage->exists($path), Response::HTTP_NOT_FOUND);

        if ($temporaryUrl) {
            return $this->storage()->temporaryUrl($path, $expiration);
        }

        $mimeType = $storage->mimeType($path);
        $pathinfo = pathinfo($path);
        $basename = $pathinfo['basename'];
        $filename = $pathinfo['filename'];

        $headers = [
            'Cache-Control' => 'public',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => 'attachment; filename=' . $filename,
            'Content-Type' => $mimeType,
        ];

        return $storage->download($basename, $filename, $headers);
    }

    public function getSize(string $path): int
    {
        return $this->storage()->size($path);
    }

    /**
     * Save single file in bucket.
     *
     * @param UploadedFile $file
     *
     * @return array|null
     *
     * @throws CannotWriteFileException
     */
    public function saveInBucket(UploadedFile $file): ?array
    {
        $uploadedFileInfo = $this->getUploadedFileInfo($file);

        $fileSaved = $this->storage()->put(
            path: $this->getFolder() . $uploadedFileInfo['name'],
            contents: $file->getContent()
        );

        if (!$fileSaved) {
            throw new CannotWriteFileException("Can't save file");
        }

        return ['path' => $this->url($uploadedFileInfo['name'])] + $uploadedFileInfo;
    }

    /**
     * @param string $base64data
     *
     * @return array|null
     *
     * @throws FileNotFoundException|CannotWriteFileException
     */
    public function saveBase64File(string $base64data): array|null
    {
        if (str_contains($base64data, ';base64')) {
            [, $base64data] = explode(';', $base64data);
            [, $base64data] = explode(',', $base64data);
        }

        if (false === base64_decode($base64data, true)) {
            return null;
        }

        if (base64_encode(base64_decode($base64data)) !== $base64data) {
            return null;
        }

        $tmpFile = $this->createFileFromBinary(base64_decode($base64data));

        $storedFile = $this->saveInBucket(
            file: $this->createUploadedFileInstance(
                path: $tmpFile['tmpPathName'],
                originalName: $tmpFile['tmpFileName'],
                mimeType: $tmpFile['tmpFileMimeType'],
            ),
        );

        unlink($tmpFile['tmpPathName']);

        return $storedFile;
    }

    /**
     * @param UploadedFile $file
     *
     * @return array
     */
    public function getUploadedFileInfo(UploadedFile $file): array
    {
        $fileDTO = new FileDTO(
            name: $this->hashName($file),
            originalName: $file->getClientOriginalName(),
            mime: $file->getMimeType(),
            size: $file->getSize(),
        );

        return $fileDTO->toArray();
    }

    public function hashName(UploadedFile $file): string
    {
        $originalName = Str::of($file->getClientOriginalName())->beforeLast('.')->toString();
        $extension = $file->extension();

        return md5($originalName) . "-$originalName.$extension";
    }

    protected function decryptFileName(string $fileName): string
    {
        return Str::of($fileName)->after('-')->toString();
    }

    public function storage(): LaravelFilesystem
    {
        return Storage::disk($this->getBucket());
    }

    /**
     * @param string $fileBinaryData
     * @return array
     */
    protected function createFileFromBinary(string $fileBinaryData): array
    {
        $tmpFileName = tempnam(sys_get_temp_dir(), 'mediafile');
        file_put_contents($tmpFileName, $fileBinaryData);

        $tmpFileObject = new File($tmpFileName);

        return [
            'tmpPathName' => $tmpFileObject->getPathname(),
            'tmpFileName' => $tmpFileObject->getFilename(),
            'tmpFileMimeType' => $tmpFileObject->getMimeType(),
        ];
    }

    /**
     * Create new UploadedFile instance from given path.
     *
     * @param string $path
     * @param string $originalName
     * @param string $mimeType
     * @return UploadedFile
     */
    protected function createUploadedFileInstance(
        string $path,
        string $originalName,
        string $mimeType,
    ): UploadedFile
    {
        return new UploadedFile(
            path: $path,
            originalName: $originalName,
            mimeType: $mimeType,
            error: 0,
            test: false,
        );
    }
}
