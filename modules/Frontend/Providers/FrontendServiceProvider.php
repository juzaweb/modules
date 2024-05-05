<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Frontend\Providers;

use Illuminate\Console\Events\CommandFinished;
use Juzaweb\CMS\Contracts\LocalThemeRepositoryContract;
use Juzaweb\CMS\Support\ServiceProvider;
use Performing\TwigComponents\Configuration;

class FrontendServiceProvider extends ServiceProvider
{
    // protected array $listen = [
    //     CommandFinished::class => [
    //         'Juzaweb\Frontend\Console\Commands\ClearCompiledViews',
    //     ],
    // ];

    public function boot(): void
    {
        $currentTheme = $this->app[LocalThemeRepositoryContract::class]->currentTheme();

        Configuration::make($this->app['twig'])
            ->setTemplatesPath($currentTheme->getPath('views/components'))
            ->setTemplatesExtension('twig')
            ->useCustomTags()
            ->setup();

        if ($currentTheme->getTemplate() == 'inertia') {
            config(['inertia.ssr.bundle' => $currentTheme->getPath('assets/ssr/ssr.mjs')]);
        }
    }

    public function register(): void
    {
        //$this->loadViewsFrom(__DIR__.'/../resources/views', 'cms');
        //$this->app->register(RouteServiceProvider::class);

        $this->mergeConfigFrom(__DIR__.'/../config/theme.php', 'theme');
    }
}
