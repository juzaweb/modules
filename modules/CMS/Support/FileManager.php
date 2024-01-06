<?php

namespace Juzaweb\CMS\Support;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Juzaweb\Backend\Events\MediaWasUploaded;
use Juzaweb\CMS\Exceptions\FileManagerException;
use Juzaweb\Backend\Models\MediaFile;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use GuzzleHttp\Exception\RequestException;

class FileManager
{
    /**
     * @var Storage
     */
    protected $storage;

    protected $resource;

    protected string $resource_type;

    protected ?int $folder_id = null;

    protected ?int $user_id = null;

    protected ?int $parent_id = null;

    protected ?string $image_size = null;

    protected Client $client;

    protected bool $downloadFileUrlToServer = true;

    protected string $type = 'image';

    protected array $errors = [];

    protected ?string $disk = null;

    public static function make($file): static
    {
        return (new self())->withResource($file);
    }

    /**
     * Add file
     *
     * @param $file
     * @param  string  $type
     * @param  int|null  $folderId
     * @param  int|null  $userId
     * @param  string|null  $disk
     * @return MediaFile
     * @throws FileManagerException
     */
    public static function addFile(
        $file,
        string $type = 'image',
        int|null $folderId = null,
        int|null $userId = null,
        ?string $disk = null
    ): MediaFile {
        return (new self())
            ->withResource($file)
            ->setType($type)
            ->setFolder($folderId)
            ->setUserId($userId)
            ->setDisk($disk)
            ->save();
    }

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Add resource for upload
     *
     * @param $resource
     * @return $this
     *
     * @throws Exception
     */
    public function withResource($resource): static
    {
        $this->resource = $resource;

        if (is_a($this->resource, UploadedFile::class)) {
            $this->resource_type = 'uploaded';
        }

        if (filter_var($this->resource, FILTER_VALIDATE_URL)) {
            $this->resource_type = 'url';
        }

        if (is_string($this->resource) && file_exists($this->resource)) {
            $this->resource_type = 'path';
        }

        if (empty($this->resource_type)) {
            throw new Exception('Resource type unsupported.');
        }

        return $this;
    }

    /**
     * Set media Folder
     *
     * @param int|null $folderId
     * @return $this
     */
    public function setFolder(?int $folderId): static
    {
        if ($folderId === null || $folderId <= 0) {
            $folderId = null;
        }

        $this->folder_id = $folderId;

        return $this;
    }

    /**
     * Set media Type
     *
     * @param string|null $type
     * @return $this
     */
    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function setUserId($userId): static
    {
        $this->user_id = $userId;

        return $this;
    }

    public function getUserId(): ?int
    {
        global $jw_user;

        return $this->user_id ?? $jw_user->id;
    }

    public function setDisk(?string $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    public function setDownloadFileUrlToServer(bool $downloadFileUrlToServer): static
    {
        $this->downloadFileUrlToServer = $downloadFileUrlToServer;

        return $this;
    }

    public function setParentId(int $parentId): static
    {
        $this->parent_id = $parentId;

        return $this;
    }

    public function setImageSize(string $size): static
    {
        $this->image_size = $size;

        return $this;
    }

    /**
     * @return MediaFile
     *
     * @throws FileManagerException
     * @throws Exception
     */
    public function save(): MediaFile
    {
        if ($this->resource_type != 'url' || $this->downloadFileUrlToServer) {
            $media = $this->uploadUploadedFile();
        } else {
            $headers = get_headers($this->resource, true);
            $fileSize = $headers['Content-Length'] ?? 0;
            $fileMime = $headers['Content-Type'] ?? null;
            $fileName = jw_basename($this->resource);
            $extension = $this->getFileExtension($fileName);

            $media = MediaFile::create(
                [
                    'name' => $fileName,
                    'type' => $this->type,
                    'mime_type' => $fileMime,
                    'path' => $this->resource,
                    'size' => $fileSize,
                    'extension' => $extension,
                    'folder_id' => $this->folder_id,
                    'user_id' => $this->getUserId(),
                    'disk' => $this->disk,
                ]
            );
        }

        event(new MediaWasUploaded($media));

        return $media;
    }

    protected function uploadUploadedFile(): MediaFile
    {
        $uploadedFile = $this->makeUploadedFile();
        if (! $this->fileIsValid($uploadedFile)) {
            $this->removeUploadedFile($uploadedFile);
            throw new FileManagerException($this->errors);
        }

        $uploadFolder = $this->makeFolderUpload();
        $filename = $this->makeFilename($uploadedFile, $uploadFolder);
        $newPath = $this->getStorage()->putFileAs(
            $uploadFolder,
            $uploadedFile,
            $filename
        );

        if (config('juzaweb.filemanager.image-optimizer')
            && in_array($uploadedFile->getMimeType(), $this->getImageMimetype())
        ) {
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize($this->getStorage()->path($newPath));
        }

        try {
            $media = MediaFile::create(
                [
                    'name' => jw_basename($uploadedFile->getClientOriginalName()),
                    'type' => $this->type,
                    'mime_type' => $uploadedFile->getMimeType(),
                    'path' => $newPath,
                    'size' => $uploadedFile->getSize(),
                    'extension' => $this->getFileExtension($uploadedFile),
                    'folder_id' => $this->folder_id,
                    'user_id' => $this->getUserId(),
                    'disk' => $this->disk ?? config('juzaweb.filemanager.disk'),
                    'parent_id' => $this->parent_id,
                    'image_size' => $this->image_size ?? $this->getImageSizeFromUploadedFile($uploadedFile),
                ]
            );

            if ($media->isRoot() && $media->isImage()) {
                $this->makeThumbImage($newPath, $media);
            }
        } catch (Exception $e) {
            $this->removeUploadedFile($uploadedFile);
            throw $e;
        }

        $this->removeUploadedFile($uploadedFile);

        return $media;
    }

    public function getFileExtension(UploadedFile|string $file): string
    {
        if ($file instanceof UploadedFile) {
            $file = $file->getClientOriginalName();
        }

        return jw_basename(pathinfo($file, PATHINFO_EXTENSION));
    }

    protected function makeThumbImage(string $path, MediaFile $media): void
    {
        $thumbPath = get_media_image_with_size(
            $path,
            '150x150',
            'path'
        );

        $img = Image::make($this->getStorage()->path($path));
        $img->resize(
            150,
            null,
            function ($constraint) {
                $constraint->aspectRatio();
            }
        );

        $img->save($thumbPath, 100);

        self::make($thumbPath)
            ->setParentId($media->id)
            ->setFolder($this->folder_id)
            ->setUserId($this->getUserId())
            ->setType($this->type)
            ->setDisk($this->disk)
            ->save();
    }

    protected function getImageMimetype()
    {
        return config('juzaweb.filemanager.types.image.valid_mime');
    }

    protected function makeFolderUpload(): string
    {
        $folderPath = date('Y/m/d');
        // Make Directory if not exists
        if (! $this->getStorage()->exists($folderPath)) {
            File::makeDirectory($this->getStorage()->path($folderPath), 0775, true);
        }
        return $folderPath;
    }

    /**
     * Make Uploaded File
     * @return UploadedFile
     * @throws Exception
     */
    protected function makeUploadedFile(): UploadedFile
    {
        return match ($this->resource_type) {
            'path' => $this->makeUploadedFileByPath(),
            'url' => $this->makeUploadedFileByUrl(),
            default => $this->resource,
        };
    }

    /**
     * Make Uploaded File By Path
     * @return UploadedFile
     */
    protected function makeUploadedFileByPath(): UploadedFile
    {
        return (new UploadedFile($this->resource, basename($this->resource)));
    }

    /**
     * Make Uploaded File By Url
     * @return UploadedFile
     *
     * @throws Exception
     */
    protected function makeUploadedFileByUrl(): UploadedFile
    {
        $content = $this->getContentFileUrl($this->resource);

        if (empty($content)) {
            throw new \RuntimeException("Can't get file url: {$this->resource}");
        }

        $tempName = jw_basename($this->resource);
        if (empty(File::extension($tempName))) {
            throw new \RuntimeException("Can't get file extension: {$this->resource}");
        }

        $this->getStorage('tmp')->put($tempName, $content);
        return (new UploadedFile($this->getStorage('tmp')->path($tempName), $tempName));
    }

    protected function makeFilename(UploadedFile $file, string $uploadFolder): string|null
    {
        $filename = $file->getClientOriginalName();
        $extension = $this->getFileExtension($file);
        $name = str_replace('.' . $extension, '', $filename);
        $name = Str::slug(substr($name, 0, 100));

        $i = 0;
        while (1) {
            $filename = $name . ($i > 0 ? "-{$i}": '') .'.'. $extension;
            if (!$this->getStorage()->exists("{$uploadFolder}/{$filename}")) {
                break;
            }
            $i++;
        }

        return $this->replaceInsecureSuffix($filename);
    }

    protected function replaceInsecureSuffix($name): string|null
    {
        return preg_replace("/\.php$/i", '', $name);
    }

    protected function fileIsValid($file): bool
    {
        if (empty($file)) {
            $this->errors[] = $this->errorMessage('file-empty');
            return false;
        }

        if (! $file instanceof UploadedFile) {
            $this->errors[] = $this->errorMessage('instance');
            return false;
        }

        if ($file->getError() != UPLOAD_ERR_OK) {
            $msg = 'File failed to upload. Error code: ' . $file->getError();
            $this->errors[] = $msg;
            return false;
        }

        $mimetype = $file->getMimeType();
        $extension = $this->getFileExtension($file);

        $config = config("juzaweb.filemanager.types.{$this->type}");
        if (empty($config)) {
            $this->errors[] = $this->errorMessage('not-supported');
            return false;
        }

        // Bytes to MB
        $max_size = $config['max_size'];
        $fileSize = $file->getSize();

        if ((config('juzaweb.filemanager.total_size') !== -1) && $fileSize > MediaFile::totalFree(false)) {
            $this->errors[] = $this->errorMessage('total-size');
            return false;
        }

        $validMimetypes = $config['valid_mime'] ?? [];
        $extensions = $config['extensions'] ?? [];

        if (in_array($mimetype, $validMimetypes) === false) {
            $this->errors[] = $this->errorMessage('mime').$mimetype;
            return false;
        }

        if ($extensions && !in_array($extension, $extensions)) {
            $this->errors[] = $this->errorMessage('extension').$extension;
            return false;
        }

        if ($max_size > 0 && $fileSize > ($max_size * 1024 * 1024)) {
            $this->errors[] = $this->errorMessage('size');
            return false;
        }

        return true;
    }

    protected function getContentFileUrl($url): bool|string
    {
        try {
            $content = $this->client->get(
                $url,
                [
                    'timeout' => 10,
                    'connect_timeout' => 10,
                ]
            )
                ->getBody()
                ->getContents();
        } catch (RequestException $e) {
            $content = false;
        }

        return $content;
    }

    protected function errorMessage($key): array|string
    {
        return trans('cms::filemanager.error-' . $key);
    }

    protected function removeUploadedFile(UploadedFile $file): void
    {
        if ($this->resource_type != 'uploaded') {
            @unlink($file->getRealPath());
        }
    }

    protected function getImageSizeFromUploadedFile(UploadedFile $file): ?string
    {
        if (in_array($file->getMimeType(), $this->getImageMimetype())) {
            try {
                $height = Image::make($file)->height();
                $width = Image::make($file)->width();
                return "{$width}x{$height}";
            } catch (\Throwable $e) {
                return null;
            }
        }

        return null;
    }

    protected function getStorage(?string $disk = null): Filesystem|Storage|FilesystemAdapter
    {
        if (isset($disk)) {
            return Storage::disk($disk);
        }

        if (isset($this->storage)) {
            return $this->storage;
        }

        $this->storage = Storage::disk($this->disk ?? config('juzaweb.filemanager.disk'));

        return $this->storage;
    }
}
