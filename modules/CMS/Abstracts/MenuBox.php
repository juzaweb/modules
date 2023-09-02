<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Abstracts;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Juzaweb\Backend\Models\MenuItem;

abstract class MenuBox
{
    /**
     * Get data from request
     *
     * @param  array  $data
     * @return array
     *
     * Return multi data to map menu_items table
     */
    abstract public function mapData(array $data): array;

    /**
     * Get data for item menu
     *
     * @param  array  $item  //
     *
     *
     * @return array
     * Return data to map menu_items table
     */
    abstract public function getData(array $item): array;

    /**
     * Get view for add item
     *
     * @return View
     */
    abstract public function addView(): Factory|View;

    /**
     * Get view for edit item
     *
     * @param  MenuItem  $item
     * @return View
     */
    abstract public function editView(MenuItem $item): Factory|View;

    /**
     * Get link for item
     *
     * @param  Collection<MenuItem>  $menuItems
     * @return array|Collection // array url
     */
    abstract public function getLinks(Collection $menuItems): array|Collection;
}
