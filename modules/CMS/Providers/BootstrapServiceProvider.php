<?php

namespace Juzaweb\CMS\Providers;

use Illuminate\Support\ServiceProvider;
use Juzaweb\CMS\Contracts\LocalPluginRepositoryContract;
use Juzaweb\CMS\Contracts\LocalThemeRepositoryContract;
use Juzaweb\CMS\Facades\ActionRegister;
use Juzaweb\Frontend\Providers\RouteServiceProvider;

class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * Booting the package.
     */
    public function boot(): void
    {
        if (config('juzaweb.plugin.enable')) {
            $this->app[LocalPluginRepositoryContract::class]->boot();
        }

        $this->booted(
            function () {
                ActionRegister::init();

                do_action('juzaweb.init');
            }
        );
    }

    /**
     * Register the provider.
     */
    public function register(): void
    {
        if (config('juzaweb.plugin.enable')) {
            $this->app[LocalPluginRepositoryContract::class]->register();
        }

        $this->app[LocalThemeRepositoryContract::class]->register();

        // Register frontend routes after load plugins
        $this->app->register(RouteServiceProvider::class);
    }
}
