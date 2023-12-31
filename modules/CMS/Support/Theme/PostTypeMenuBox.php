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
use Juzaweb\CMS\Facades\HookAction;

class PostTypeMenuBox extends MenuBox
{
    protected string $key;
    protected Collection $postType;

    public function __construct($key, $postType)
    {
        $this->key = $key;
        $this->postType = $postType;
    }

    public function mapData(array $data): array
    {
        $result = [];
        $items = $data['items'];
        $query = app($this->postType->get('model'))->query();
        $items = $query->whereIn('id', $items)->get();

        foreach ($items as $item) {
            $result[] = $this->getData([
                'label' => $item->getTitle(),
                'model_id' => $item->id,
            ]);
        }

        return $result;
    }

    public function getData(array $item): array
    {
        return [
            'label' => $item['label'],
            'model_class' => $this->postType->get('model'),
            'model_id' => $item['model_id'],
        ];
    }

    public function addView(): Factory|View
    {
        $items = app($this->postType->get('model'))
            ->where('type', $this->postType->get('key'))
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return view('cms::backend.menu.boxs.post_type_add', [
            'key' => $this->key,
            'postType' => $this->postType,
            'items' => $items,
        ]);
    }

    public function editView(MenuItem $item): View
    {
        return view('cms::backend.menu.boxs.post_type_edit', [
            'item' => $item,
            'postType' => $this->postType,
        ]);
    }

    public function getLinks(Collection $menuItems): Collection
    {
        $permalink = HookAction::getPermalinks($this->postType->get('key'));

        if ($permalink === null) {
            $base = '';
        } else {
            $base = $permalink->get('base');
        }

        $query = app($this->postType->get('model'))->query();
        $items = $query->whereIn('id', $menuItems->pluck('model_id')->toArray())
            ->get(['id', 'slug'])->keyBy('id');

        return $menuItems->map(function ($item) use ($base, $items) {
            if (!empty($items[$item->model_id])) {
                $item->link = url()->to($base.'/'.$items[$item->model_id]->slug);
            }

            return $item;
        });
    }
}
