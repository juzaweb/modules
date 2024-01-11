<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Facades;

use Juzaweb\CMS\Support\HookActions\Builder;

/**
 * @see \Juzaweb\CMS\Support\HookActions\Builder
 */
class ActionBuilder
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return Builder::class;
    }
}
