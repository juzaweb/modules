<?php

namespace Juzaweb\CMS\Providers;

use Juzaweb\CMS\Contracts\ActivatorInterface;
use Juzaweb\CMS\Contracts\ConfigContract;
use Juzaweb\CMS\Contracts\LocalPluginRepositoryContract;
use Juzaweb\CMS\Exceptions\InvalidActivatorClass;
use Juzaweb\CMS\Support\LocalPluginRepository;
use Juzaweb\CMS\Support\ServiceProvider;

class PluginServiceProvider extends ServiceProvider
{
    /**
     * Booting the package.
     */
    public function boot()
    {
        $this->registerModules();
    }

    /**
     * Register all plugins.
     */
    protected function registerModules(): void
    {
        $this->app->register(BootstrapServiceProvider::class);
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerNamespaces();
        $this->registerServices();
    }

    /**
     * Register package's namespaces.
     */
    protected function registerNamespaces(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../../config/plugin.php', 'plugin');
    }

    protected function registerServices(): void
    {
        $this->app->singleton(
            LocalPluginRepositoryContract::class,
            function ($app) {
                $path = config('juzaweb.plugin.path');
                return new LocalPluginRepository($app, $path);
            }
        );

        $this->app->singleton(
            ActivatorInterface::class,
            function ($app) {
                $class = config('plugin.activator');
                if ($class === null) {
                    throw InvalidActivatorClass::missingConfig();
                }

                return new $class($app, $app[ConfigContract::class]);
            }
        );

        $this->app->alias(LocalPluginRepositoryContract::class, 'plugins');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [LocalPluginRepositoryContract::class, 'plugins'];
    }
}
