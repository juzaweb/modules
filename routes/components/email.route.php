<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://github.com/juzaweb/cms
 * @license    GNU V2
 */

use Juzaweb\Backend\Http\Controllers\Backend\Email\EmailController;
use Juzaweb\Backend\Http\Controllers\Backend\Email\EmailHookController;
use Juzaweb\Backend\Http\Controllers\Backend\Email\EmailTemplateController;

Route::group(
    ['prefix' => 'email'],
    function () {
        Route::post('/', [EmailController::class, 'save'])->name('admin.setting.email.save');
        Route::post('send-test-mail', [EmailController::class, 'sendTestMail'])
            ->name('admin.setting.email.test-email');
    }
);

Route::jwResource('email-template', EmailTemplateController::class);
Route::jwResource('email-hooks', EmailHookController::class);
