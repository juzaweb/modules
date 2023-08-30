<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://github.com/juzaweb/cms
 * @license    GNU V2
 */

use Juzaweb\Backend\Http\Controllers\FileManager\FileManagerController;
use Juzaweb\Backend\Http\Controllers\FileManager\UploadController;
use Juzaweb\Backend\Http\Controllers\FileManager\ItemsController;
use Juzaweb\Backend\Http\Controllers\FileManager\FolderController;
use Juzaweb\Backend\Http\Controllers\FileManager\DeleteController;

Route::group(
    ['prefix' => 'file-manager'],
    function () {
        Route::get('/', [FileManagerController::class, 'index']);

        Route::get('/errors', [FileManagerController::class, 'getErrors']);

        Route::any('/upload', [UploadController::class, 'upload'])->name('filemanager.upload');

        Route::any('/import', [UploadController::class, 'import'])->name('filemanager.import');

        Route::get('/jsonitems', [ItemsController::class, 'getItems']);

        /*Route::get('/move', 'ItemsController@move');

        Route::get('/domove', 'ItemsController@domove');*/

        Route::post('/newfolder', [FolderController::class, 'addfolder']);

        Route::get('/folders', [FolderController::class, 'getFolders']);

        /*Route::get('/rename', 'RenameController@getRename');

        Route::get('/download', 'DownloadController@getDownload');*/

        Route::post('/delete', [DeleteController::class, 'delete']);
    }
);
