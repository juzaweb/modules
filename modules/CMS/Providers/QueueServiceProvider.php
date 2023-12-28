<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Providers;

use Illuminate\Queue\QueueManager;
use Illuminate\Queue\QueueServiceProvider as BaseQueueServiceProvider;
use Juzaweb\CMS\Support\Queues\DatabaseConnector;

class QueueServiceProvider extends BaseQueueServiceProvider
{
    /**
     * Register the database queue connector.
     * @NOTE:-This will be called automatically.
     * We override DatabaseConnector,registerRedisConnector method so that we can add custom code.
     * Will add custom  as well
     * @param  QueueManager  $manager
     * @return void
     */
    protected function registerDatabaseConnector($manager): void
    {
        $manager->addConnector('database', function () {
            return new DatabaseConnector($this->app['db']);
        });
    }
}
