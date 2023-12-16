<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Network\Traits;

use Juzaweb\Network\Facades\Network;
use Juzaweb\Network\Observers\SubsiteModelObserver;
use Juzaweb\Network\Scopes\SubsiteQueryScope;

trait Networkable
{
    public static function bootNetworkable(): void
    {
        if (config('network.enable')) {
            static::addGlobalScope(new SubsiteQueryScope());
            static::observe([SubsiteModelObserver::class]);
        }
    }

    public function getConnectionName(): ?string
    {
        if (config('network.enable') && !Network::isRootSite()) {
            return 'subsite';
        }

        return $this->connection;
    }
}
