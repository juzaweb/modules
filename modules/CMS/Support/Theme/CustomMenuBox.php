<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Support\Theme;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Juzaweb\Backend\Models\MenuItem;
use Juzaweb\CMS\Abstracts\MenuBox;

class CustomMenuBox extends MenuBox
{
    public function mapData(array $data): array
    {
        $result[] = $this->getData($data);

        return $result;
    }

    public function getData(array $item): array
    {
        return [
            'label' => $item['label'],
            'link' => $item['link'],
        ];
    }

    public function addView(): Factory|View
    {
        return view('cms::backend.menu.boxs.custom_add');
    }

    public function editView(MenuItem $item): Factory|View
    {
        return view('cms::backend.menu.boxs.custom_edit', [
            'item' => $item,
        ]);
    }

    public function getLinks(Collection $menuItems): array|Collection
    {
        return $menuItems;
    }
}
