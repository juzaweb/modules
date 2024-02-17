<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\API\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function map(): void
    {
        $this->mapAdminRoutes();
        $this->mapApiRoutes();
        $this->mapThemeRoutes();
    }

    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->as('api.')
            ->group(__DIR__.'/../routes/api.php');
    }

    protected function mapAdminRoutes(): void
    {
        Route::prefix(config('juzaweb.admin_prefix'))
            ->middleware('admin')
            ->group(__DIR__.'/../routes/admin.php');
    }

    protected function mapThemeRoutes(): void
    {
        Route::middleware('theme')
            ->prefix(config('theme.route_prefix'))
            ->group(__DIR__ . '/../routes/theme.php');
    }
}
