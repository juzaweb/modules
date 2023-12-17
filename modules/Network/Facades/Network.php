<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Network\Facades;

use Illuminate\Support\Facades\Facade;
use Juzaweb\Network\Contracts\NetworkRegistionContract;

/**
 * @method static void init(?string $site = null)
 * @method static bool isRootSite(?string $domain = null)
 * @method static bool isSubSite(?string $domain = null)
 * @method static string getCurrentDomain()
 * @method static string getRootConnection()
 * @method static object getCurrentSite()
 * @method static null|int getCurrentSiteId()
 * @see \Juzaweb\Network\Support\NetworkRegistion
 */
class Network extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return NetworkRegistionContract::class;
    }
}
