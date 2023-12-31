<?php

namespace Juzaweb\Backend\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    //protected $namespace = 'Juzaweb\Backend\Http\Controllers';

    public function boot(): void
    {
        //
    }

    public function map(): void
    {
        $this->mapWebRoutes();
        $this->mapAdminRoutes();
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            //->namespace($this->namespace)
            ->group(__DIR__ . '/../../../routes/web.php');
    }

    protected function mapAdminRoutes(): void
    {
        Route::middleware('admin')
            //->namespace($this->namespace)
            ->prefix(config('juzaweb.admin_prefix'))
            ->group(__DIR__ . '/../../../routes/admin.php');
    }
}
