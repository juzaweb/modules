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

use Juzaweb\CMS\Models\User;
use Juzaweb\Network\Models\Site;
use Juzaweb\Network\Support\NetworkSite;

/**
 * @see NetworkSite
 */
interface NetworkSiteContract
{
    public function getLoginUrl(User $user): string;

    public function adminUrl(): string;

    public function model(): Site;

    public function getUrl(string $path = null): string;
}
