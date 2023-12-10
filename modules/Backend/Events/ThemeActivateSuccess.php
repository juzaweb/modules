<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Backend\Events;

use Juzaweb\CMS\Interfaces\Theme\ThemeInterface;

class ThemeActivateSuccess
{
    public function __construct(public ThemeInterface $theme)
    {
    }
}
