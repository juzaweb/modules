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
use Juzaweb\Backend\Commands\AutoTagCommand;
use Juzaweb\Backend\Commands\SEO;
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
        SEO\AutoSubmitUrlGoogle::class,
        SEO\AutoSubmitUrlBing::class,
    ];

    public function boot(): void
    {
        $this->app->booted(
            function () {
                $schedule = $this->app->make(Schedule::class);
                //$schedule->command(Commands\AutoClearSlotCommand::class)->hourly();

                if (get_config('jw_auto_add_tags_to_posts')) {
                    $schedule->command(AutoTagCommand::class)->dailyAt('03:16');
                }

                if (get_config('jw_auto_ping_google_sitemap')) {
                    $schedule->command(SEO\AutoPingSitemapCommand::class)->weeklyOn([1, 3, 5]);
                }

                if (get_config('jw_auto_submit_url_google')) {
                    $schedule->command(SEO\AutoSubmitUrlGoogle::class)->dailyAt('01:00');
                }

                if (get_config('jw_auto_submit_url_bing')) {
                    $schedule->command(SEO\AutoSubmitUrlBing::class)->dailyAt('01:00');
                }

                if (get_config('jw_backup_enable')) {
                    $schedule->command('backup:clean')->daily();
                    $time = get_config('jw_backup_time', 'daily');
                    switch ($time) {
                        case 'weekly':
                            $schedule->command('backup:run')->weekly();
                            break;
                        case 'monthly':
                            $schedule->command('backup:run')->monthly();
                            break;
                        default:
                            $schedule->command('backup:run')->daily();
                    }
                }
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
