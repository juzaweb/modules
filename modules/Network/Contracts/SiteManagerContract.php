<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Network\Contracts;

use Juzaweb\Network\Models\Site;

/**
 * @see \Juzaweb\Network\Support\SiteManager
 */
interface SiteManagerContract
{
    public function find(string|int|Site $site): ?NetworkSiteContract;

    /**
     * @param  string  $subdomain
     * @param  array  $args
     * @return NetworkSiteContract
     * @see \Juzaweb\Network\Support\SiteManager::create
     */
    public function create(string $subdomain, array $args = []): NetworkSiteContract;

    public function getCreater(): SiteCreaterContract;
}
