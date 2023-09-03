<?php

use Juzaweb\Backend\Http\Controllers\Backend\Tool\ImportController;

Route::get('imports', [ImportController::class, 'index']);
Route::post('imports', [ImportController::class, 'import']);
