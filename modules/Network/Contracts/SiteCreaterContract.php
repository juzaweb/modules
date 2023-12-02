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
 * @see \Juzaweb\Network\Support\SiteCreater
 */
interface SiteCreaterContract
{
    /**
     * Creates a new site with the given subdomain and optional arguments.
     *
     * @param  string  $subdomain  The subdomain for the new site.
     * @param  array  $args  Optional arguments for creating the site.
     * @return Site The newly created site.
     * @see \Juzaweb\Network\Support\SiteCreater::create()
     */
    public function create(string $subdomain, array $args = []): Site;

    /**
     * Set up the site.
     *
     * @param  Site  $site  The site object to set up.
     * @param  array  $args  Optional arguments to customize the setup.
     * @return void
     */
    public function setupSite(Site $site, array $args = []): void;
}
