<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Console;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;

class Composer
{
    public static function postPackageUpdate(PackageEvent $event)
    {
        $updatePackage = $event->getOperation()->getPackage();
        // do stuff
    }
}
