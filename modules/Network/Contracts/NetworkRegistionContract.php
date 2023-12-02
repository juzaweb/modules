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

    public function getCurrentSiteId(): ?int;

    public function getCurrentSite(): object;

    public function isRootSite($domain = null): bool;

    public function getCurrentDomain(): string;
}
