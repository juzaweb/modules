<?php

namespace Juzaweb\Backend\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Juzaweb\CMS\Models\Model;

/**
 * Juzaweb\Backend\Models\MediaFile
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $mime_type
 * @property string $path
 * @property string $extension
 * @property int $size
 * @property int|null $folder_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|MediaFile newModelQuery()
 * @method static Builder|MediaFile newQuery()
 * @method static Builder|MediaFile query()
 * @method static Builder|MediaFile whereCreatedAt($value)
 * @method static Builder|MediaFile whereExtension($value)
 * @method static Builder|MediaFile whereFolderId($value)
 * @method static Builder|MediaFile whereId($value)
 * @method static Builder|MediaFile whereMimeType($value)
 * @method static Builder|MediaFile whereName($value)
 * @method static Builder|MediaFile wherePath($value)
 * @method static Builder|MediaFile whereSize($value)
 * @method static Builder|MediaFile whereType($value)
 * @method static Builder|MediaFile whereUpdatedAt($value)
 * @method static Builder|MediaFile whereUserId($value)
 * @property int|null $site_id
 * @method static Builder|MediaFile whereSiteId($value)
 * @mixin Eloquent
 */
class MediaFile extends Model
{
    protected $table = 'media_files';
    protected $fillable = [
        'name',
        'path',
        'extension',
        'mime_type',
        'user_id',
        'folder_id',
        'type',
        'size',
        'disk',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function delete(): bool
    {
        $this->deleteFile();

        return parent::delete();
    }

    public function deleteFile(): bool
    {
        return Storage::disk($this->disk ?? config('juzaweb.filemanager.disk'))->delete($this->path);
    }

    public function isImage(): bool
    {
        return in_array(
            $this->mime_type,
            config('juzaweb.filemanager.types.image.valid_mime')
        );
    }
}
