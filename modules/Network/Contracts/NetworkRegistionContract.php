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

/**
 * @see \Juzaweb\Network\Support\NetworkRegistion
 */
interface NetworkRegistionContract
{
    public function init(?string $site = null): void;

    public function getCurrentSiteId(): int;

    /**
     * Retrieves the current site.
     *
     * @return object The current site.
     */
    public function getCurrentSite(): ?object;

    /**
     * Determines if the given domain is the root site.
     *
     * @param  string|null  $domain  The domain to check. If null, it checks the current site's domain.
     * @return bool Returns true if the domain is the root site, false otherwise.
     */
    public function isRootSite(string $domain = null): bool;

    /**
     * Check if the given domain is a sub-site.
     *
     * @param  string|null  $domain  The domain to check. Defaults to null.
     * @return bool Returns true if the domain is a sub-site, false otherwise.
     */
    public function isSubSite(string $domain = null): bool;

    public function getCurrentDomain(): string;
}
