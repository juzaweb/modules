<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

use Juzaweb\Network\Facades\Network;

if (!function_exists('is_root_site')) {
    function is_root_site(?string $domain = null): bool
    {
        return Network::isRootSite($domain);
    }
}

if (!function_exists('get_current_site_id')) {
    function get_current_site_id(): int
    {
        return Network::getCurrentSiteId();
    }
}
