<?php

use Juzaweb\Backend\Http\Controllers\Backend\Email\EmailLogController;

Route::get('logs/email', [EmailLogController::class, 'index'])->name('admin.logs.email');
