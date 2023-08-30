<?php

use Juzaweb\Backend\Http\Controllers\Backend\ImportController;

Route::get('imports', [ImportController::class, 'index']);
Route::post('imports', [ImportController::class, 'import']);
