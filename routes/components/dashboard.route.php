<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://github.com/juzaweb/cms
 * @license    GNU V2
 */

use Juzaweb\Backend\Http\Controllers\Backend\DashboardController;
use Juzaweb\Backend\Http\Controllers\Backend\DatatableController;
use Juzaweb\Backend\Http\Controllers\Backend\LoadDataController;
use Juzaweb\Backend\Http\Controllers\Backend\AjaxController;

Route::group(
    ['prefix' => '/'],
    function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/load-data/{func}', [LoadDataController::class, 'loadData'])->name('admin.load_data');

        Route::get(
            '/dashboard/users',
            [DashboardController::class, 'getDataUser']
        )
            ->name('admin.dashboard.users');

        Route::get(
            '/dashboard/top-views',
            [DashboardController::class, 'getDataTopViews']
        )->name('admin.dashboard.top_views');

        Route::get(
            '/dashboard/views-chart',
            [DashboardController::class, 'viewsChart']
        )->name('admin.dashboard.views_chart');

        Route::get(
            '/datatable/get-data',
            [DatatableController::class, 'getData']
        )
            ->name('admin.datatable.get-data');

        Route::post(
            '/datatable/bulk-actions',
            [DatatableController::class, 'bulkActions']
        )->name('admin.datatable.bulk-actions');

        Route::post(
            '/remove-message',
            [DashboardController::class, 'removeMessage']
        )->name('admin.dashboard.remove-message');
    }
);

Route::any('/ajax/{slug}', [AjaxController::class, 'handle'])
    ->name('admin.ajax')
    ->where('slug', '[a-z0-9\-\/]+');
