<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\CMS\Providers;

use Juzaweb\CMS\Contracts\ShortCode as ShortCodeContract;
use Juzaweb\CMS\Contracts\ShortCodeCompiler as ShortCodeCompilerContract;
use Juzaweb\CMS\Support\ServiceProvider;
use Juzaweb\CMS\Support\ShortCode\Compilers\ShortCodeCompiler;
use Juzaweb\CMS\Support\ShortCode\ShortCode;

class ShortCodeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            ShortCodeCompilerContract::class,
            ShortCodeCompiler::class
        );

        $this->app->singleton(
            ShortCodeContract::class,
            function ($app) {
                return new ShortCode($app[ShortCodeCompilerContract::class]);
            }
        );
    }
}
