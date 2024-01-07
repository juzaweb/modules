<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Network\Providers;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Queue\Jobs\DatabaseJob;
use Illuminate\Support\Facades\Queue;
use Juzaweb\CMS\Contracts\OverwriteConfigContract;
use Juzaweb\CMS\Facades\ActionRegister;
use Juzaweb\CMS\Support\Application;
use Juzaweb\CMS\Support\ServiceProvider;
use Juzaweb\Network\Actions\ConfigAction;
use Juzaweb\Network\Actions\NetworkAction;
use Juzaweb\Network\Commands;
use Juzaweb\Network\Contracts\NetworkConfig as NetworkConfigContract;
use Juzaweb\Network\Contracts\NetworkRegistionContract;
use Juzaweb\Network\Contracts\SiteCreaterContract;
use Juzaweb\Network\Contracts\SiteManagerContract;
use Juzaweb\Network\Contracts\SiteSetupContract;
use Juzaweb\Network\Facades\Network;
use Juzaweb\Network\Models\Site;
use Juzaweb\Network\Observers\SiteModelObserver;
use Juzaweb\Network\Support\NetworkConfig;
use Juzaweb\Network\Support\NetworkRegistion;
use Juzaweb\Network\Support\SiteCreater;
use Juzaweb\Network\Support\SiteManager;
use Juzaweb\Network\Support\SiteSetup;
use Illuminate\Queue\Events\JobProcessing;

class NetworkServiceProvider extends ServiceProvider
{
    protected array $commands = [
        Commands\MakeSiteCommand::class,
        Commands\ArtisanCommand::class,
        Commands\InstallCommand::class,
        Commands\MakeDatabaseCommand::class,
        Commands\MigrateCommand::class,
    ];

    public function boot(): void
    {
        Network::init();

        $this->commands($this->commands);

        Site::observe([SiteModelObserver::class]);

        ActionRegister::register([NetworkAction::class, ConfigAction::class]);

        if (config('network.enable')) {
            Queue::before(function (JobProcessing $event) {
                if (!$event->job instanceof DatabaseJob) {
                    return;
                }

                if (empty($event->job->getJobRecord()->__get('site_id'))) {
                    return;
                }

                $site = $this->app['db']->table('network_sites')->find($event->job->getJobRecord()->site_id);

                $this->app[SiteSetupContract::class]->setup($site);
            });
        }
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);

        $paths = glob(__DIR__ . '/../Database/migrations/*', GLOB_ONLYDIR);
        $this->loadMigrationsFrom($paths);

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'network');

        $this->app->singleton(NetworkConfigContract::class, NetworkConfig::class);

        $this->app->singleton(
            SiteSetupContract::class,
            function ($app) {
                return new SiteSetup(
                    $app['config'],
                    $app['db'],
                    $app[OverwriteConfigContract::class]
                );
            }
        );

        $this->app->singleton(SiteCreaterContract::class, SiteCreater::class);

        $this->app->singleton(
            NetworkRegistionContract::class,
            function (Application $app) {
                return new NetworkRegistion(
                    $app,
                    $app['config'],
                    $app['request'],
                    $app['cache'],
                    $app['db'],
                    $app[SiteSetupContract::class],
                    $app[Kernel::class],
                    $app['session'],
                    $app[UrlGenerator::class]
                );
            }
        );

        $this->app->singleton(
            SiteManagerContract::class,
            function ($app) {
                return new SiteManager(
                    $app['db'],
                    $app[SiteCreaterContract::class],
                    $app[NetworkRegistionContract::class]
                );
            }
        );
    }
}
