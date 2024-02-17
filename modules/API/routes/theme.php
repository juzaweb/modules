<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

use Juzaweb\API\Http\Controllers\Documentation\SwaggerAssetController;
use Juzaweb\API\Http\Controllers\Documentation\SwaggerDocumentController;

Route::get('/json/{document}', [SwaggerDocumentController::class, 'index'])
    ->name('admin.api.documentation.json');
Route::get('/asset/{asset}', [SwaggerAssetController::class, 'index'])
    ->name('l5-swagger.default.asset');
