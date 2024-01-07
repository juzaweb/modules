<?php

use Juzaweb\Network\Http\Controllers\SiteController;

Route::get('token-login', [SiteController::class, 'loginToken'])
    ->name('network.sites.login-with-token')
    ->prefix(config('juzaweb.admin_prefix'));
