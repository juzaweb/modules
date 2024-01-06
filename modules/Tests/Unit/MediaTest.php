<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Tests\Unit;

use Illuminate\Support\Facades\Storage;
use Juzaweb\CMS\Models\User;
use Juzaweb\CMS\Support\FileManager;
use Juzaweb\Tests\TestCase;

class MediaTest extends TestCase
{
    public function testUploadByPath(): void
    {
        Storage::put('tmps/test.gif', base64_decode('R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=='));

        $user = User::first();

        $this->printText("Upload to User: " . $user->id);

        $media = FileManager::addFile(
            Storage::path('tmps/test.gif'),
            'file',
            null,
            $user->id
        );

        $this->assertNotEmpty($media->path);

        $this->assertFileDoesNotExist(Storage::path('tmps/test.gif'));
    }

    public function testUploadByUrl(): void
    {
        $img = 'https://cdn.juzaweb.com/jw-styles/juzaweb/images/thumb-default.png';

        $media = FileManager::addFile($img, 'image', null, User::first()->id);

        $this->assertNotEmpty($media->path);

        $this->assertFileExists(
            Storage::disk('public')->path($media->path)
        );
    }
}
