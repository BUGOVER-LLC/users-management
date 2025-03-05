<?php

declare(strict_types=1);

namespace App\Core\FileSystem;

use App\Core\FileSystem\Contracts\FileSystemContract;
use Aws\S3\S3Client;
use DateTimeInterface;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem as LaravelFilesystem;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use League\Flysystem\UnableToCopyFile;
use Symfony\Component\HttpFoundation\File\Exception\CannotWriteFileException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use ZipArchive;

use function is_array;
use function is_string;

final class FileSystem implements FileSystemContract
{
    protected string $disk;

    protected null|string $folder = null;

    public function __construct()
    {
        $this->disk = 's3';
    }

    public function setDisk(string $disk): FileSystem
    {
        $this->disk = $disk;

        return $this;
    }

    public function getDisk(): string
    {
        return $this->disk;
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

    /**
     * @return $this
     */
    public function signedDocuments(): FileSystem
    {
        $this->disk = 's3signed';

        return $this;
    }

    /**
     * @return string
     */
    public function getBucket(): string
    {
        $client = $this->client();

        return config('filesystems.disks.' . $client->getConfig('signing_name') . '.bucket');
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
                if (isset($uploadedFile['file']) && $uploadedFile['file'] instanceof UploadedFile) {
                    $uploadedFileInfo[] = $this->saveInBucket($uploadedFile['file']);
                }

                if (isset($uploadedFile['file']) && is_string($uploadedFile['file'])) {
                    $uploadedFileInfo[] = $this->saveBase64File($uploadedFile['file']);
                }
            }

            return $uploadedFileInfo;
        }

        if (is_string($file)) {
            return $this->saveBase64File($file);
        }

        return $this->saveInBucket($file);
    }

    /**
     * @param array|string $paths
     * @return bool
     */
    public function delete(array|string $paths): bool
    {
        return $this->storage()->delete($paths);
    }

    /**
     * Generates a signed URL for the given file path.
     *
     * @param string $path
     * @return string
     */
    public function url(string $path): string
    {
        return $this
            ->client()
            ->getObjectUrl(
                bucket: $this->getBucket(),
                key: $path,
            );
    }

    /**
     * @param string $path
     * @return string|null
     */
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
        null|DateTimeInterface $expiration = null
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

    /**
     * @param array $filesMoveToZip
     * @param string $zipName
     * @return string|null
     */
    public function downloadZip(array $filesMoveToZip, string $zipName = 'archive'): null|string
    {
        $zip = new ZipArchive();
        $fileName = base_path('storage') . "/$zipName.zip";

        if (true !== $zip->open($fileName, ZipArchive::CREATE)) {
            return null;
        }

        foreach ($filesMoveToZip as $fileToZip) {
            $fileToZip = explode('/', $fileToZip);
            $length = count($fileToZip) - 1;
            if ($length >= 5) {
                $fileToZip = $fileToZip[$length - 1] . '/' . $fileToZip[$length];
            } else {
                $fileToZip = $fileToZip[$length];
            }

            $storage = null;
            if ($this->storage()->exists($fileToZip) || $this->signedDocuments()->storage()->exists($fileToZip)) {
                $storage = !$this->storage()->missing($fileToZip) ? $this->storage() : $this->signedDocuments(
                )->storage();
                $fileContent = $storage->get($fileToZip);
                $zip->addFromString($fileToZip, $fileContent);
            }
        }

        if (-1 === $zip->lastId) {
            return null;
        }

        $zip->close();

        return $fileName;
    }

    /**
     * @param string $path
     * @return int
     */
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
        $client = $this->client();
        $url = config('filesystems.disks.' . $client->getConfig('signing_name') . '.url');
        $uploadedFileInfo = $this->getUploadedFileInfo($file);
        $filePathName = $this->getFolder() . $uploadedFileInfo['name'];
        $fileSaved = $this->storage()->put(
            $filePathName,
            $file->getContent(),
        );
        if (!$fileSaved) {
            throw new CannotWriteFileException("Can't save file");
        }

        return ['path' => "$url/$filePathName"] + $uploadedFileInfo;
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

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function hashName(UploadedFile $file): string
    {
        return $this->hashOriginalName($file->getClientOriginalName());
    }

    /**
     * @param $name
     * @return string
     */
    public function hashOriginalName($name): string
    {
        $originalName = Str::of($name)->beforeLast('.')->replace(' ', '-')->toString();
        $extension = Str::of($name)->afterLast('.')->toString();

        return Str::random(5) . "-$originalName.$extension";
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function decryptFileName(string $fileName): string
    {
        return Str::of($fileName)->after('-')->toString();
    }

    /**
     * @return LaravelFilesystem
     */
    public function storage(): LaravelFilesystem
    {
        return Storage::disk($this->getDisk());
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
        string $mimeType
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

    /**
     * @param $filePath
     * @param $toPath
     * @return mixed
     * @throws \Exception
     */
    public function copyTo($filePath, $toPath)
    {
        $client = $this->client();

        $copiedFile = $client->copyObject([
            'Bucket' => $this->getBucket(),
            'Key' => "{$this->getBucket()}/$toPath",
            'CopySource' => "{$this->getBucket()}/$filePath",
            'ACL' => 'public-read',
        ]);

        if ($copiedFile) {
            return $copiedFile->search('ObjectURL');
        }

        throw UnableToCopyFile::fromLocationTo($filePath, $toPath);
    }

    /**
     * @param $fileObject
     * @return mixed
     * @throws \Exception
     */
    public function copyFileObject($fileObject)
    {
        $newFileName = $this->hashOriginalName($fileObject['fileName']);

        $copiedFile = $this->copyTo($fileObject['name'], $newFileName);
        $fileObject['name'] = $newFileName;
        $fileObject['path'] = $copiedFile;
        $fileObject['uploadedAt'] = now();

        return $fileObject;
    }

    /**
     * @return S3Client
     */
    protected function client(): S3Client
    {
        return new S3Client([
            'region' => config('filesystems.disks.' . $this->getDisk() . '.region'),
            'version' => 'latest',
            'use_path_style_endpoint' => true,
            'signing_name' => $this->getDisk(),
            'url' => config('filesystems.disks.' . $this->getDisk() . '.url'),
            'endpoint' => config('filesystems.disks.' . $this->getDisk() . '.endpoint'),
            'credentials' => [
                'key' => config('filesystems.disks.' . $this->getDisk() . '.key'),
                'secret' => config('filesystems.disks.' . $this->getDisk() . '.secret'),
            ],
        ]);
    }
}
