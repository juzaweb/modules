<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Juzaweb\CMS\Console\Commands;
use Juzaweb\CMS\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    protected array $commands = [
        Commands\InstallCommand::class,
        Commands\UpdateCommand::class,
        Commands\SendMailCommand::class,
        Commands\ClearCacheCommand::class,
        Commands\PluginAutoloadCommand::class,
        Commands\AutoClearSlotCommand::class,
        Commands\ShowSlotCommand::class,
        Commands\ClearCacheExpiredCommand::class,
        Commands\VersionCommand::class,
    ];

    public function boot(): void
    {
        $this->app->booted(
            function () {
                $schedule = $this->app->make(Schedule::class);
                //$schedule->command(Commands\AutoClearSlotCommand::class)->hourly();
            }
        );
    }

    public function register(): void
    {
        $this->commands($this->commands);
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return $this->commands;
    }
}
