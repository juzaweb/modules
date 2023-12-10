<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

return [
    'enable' => env('JW_ALLOW_MULTISITE', false),

    'domain' => env('JW_NETWORK_ROOT_DOMAIN'),

    'subsite_domain' => env('JW_SUBSITE_DOMAIN'),

    'share_user_main_to_sites' => env('JW_SHARE_USER_MAIN_SITES'),

    'excepted_subdomains' => [
        'admin',
        'api',
        'auth',
        'cms',
        'dashboard',
        'test',
        'administrator',
        'admins',
    ],
];
