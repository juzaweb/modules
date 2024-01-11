<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Support\HookActions;

use Juzaweb\CMS\Contracts\HookActions\Builder as BuilderAlias;
use Juzaweb\CMS\Support\HookActions\Entities\AdminMenu;
use Juzaweb\CMS\Support\HookActions\Entities\AdminPage;

class Builder implements BuilderAlias
{
    public function adminMenu(string $title): AdminMenu
    {
        return AdminMenu::make($title);
    }

    public function adminPage(string $title): AdminPage
    {
        return AdminPage::make($title);
    }
}
