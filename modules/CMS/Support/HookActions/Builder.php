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

use Juzaweb\CMS\Support\HookActions\Entities\Menu;

class Builder
{
    public function menu(string $title): Menu
    {
        return Menu::make($title);
    }
}
