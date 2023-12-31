<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Backend\Listeners;

use Illuminate\Contracts\Filesystem\Factory;
use Intervention\Image\Facades\Image;
use Juzaweb\Backend\Events\AfterPostSave;
use Illuminate\Config\Repository;
use Juzaweb\Backend\Models\MediaFile;
use Juzaweb\CMS\Support\FileManager;

class ResizeThumbnailPostListener
{
    public function __construct(
        protected Factory $filesystem,
        protected Repository $config
    ) {
    }

    public function handle(AfterPostSave $event): void
    {
        if (empty($event->post->thumbnail) || is_url($event->post->thumbnail)) {
            return;
        }

        $resize = get_config('auto_resize_thumbnail')[$event->post->type] ?? false;
        $size = get_thumbnail_size($event->post->type);
        if (empty($resize) || empty($size['width']) || empty($size['height'])) {
            return;
        }

        if (has_media_image_size($event->post->thumbnail, "{$size['width']}x{$size['height']}")) {
            return;
        }

        $media = MediaFile::findByPath($event->post->thumbnail);
        $filePath = get_media_image_with_size(
            $event->post->thumbnail,
            "{$size['width']}x{$size['height']}",
            'path'
        );

        $imgDisk = $this->config->get('juzaweb.filemanager.disk');
        $img = Image::make($this->filesystem->disk($imgDisk)->path($event->post->thumbnail));
        $img->fit($size['width'], $size['height']);
        $img->save($filePath, 100);

        FileManager::make($filePath)
            ->setParentId($media?->id)
            ->save();
    }
}
