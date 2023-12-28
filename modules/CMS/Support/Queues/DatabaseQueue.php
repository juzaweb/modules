<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Support\Queues;

use Illuminate\Queue\DatabaseQueue as BaseDatabaseQueue;
use Juzaweb\Network\Contracts\NetworkRegistionContract;

class DatabaseQueue extends BaseDatabaseQueue
{
    /**
     * Create an array to insert for the given job.
     *
     * @param  string|null  $queue
     * @param  string  $payload
     * @param  int  $availableAt
     * @param  int  $attempts
     * @return array
     */
    protected function buildDatabaseRecord($queue, $payload, $availableAt, $attempts = 0): array
    {
        $record = parent::buildDatabaseRecord($queue, $payload, $availableAt, $attempts);

        if (config('network.enable')) {
            $record['site_id'] = app(NetworkRegistionContract::class)->getCurrentSiteId();
        }

        return $record;
    }
}
