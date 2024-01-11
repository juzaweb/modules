<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Contracts\HookActions;

use Juzaweb\CMS\Support\HookActions\Entities\AdminMenu;
use Juzaweb\CMS\Support\HookActions\Entities\AdminPage;

interface Builder
{
    public function adminMenu(string $title): AdminMenu;

    public function adminPage(string $title): AdminPage;
}
