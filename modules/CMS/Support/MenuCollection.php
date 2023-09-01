<?php

namespace Juzaweb\CMS\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class MenuCollection implements Arrayable
{
    protected Collection $item;

    /**
     * Make menu Collection
     *
     * @param array $items
     * @param string $sortBy
     * @return Collection
     */
    public static function make(array $items, string $sortBy = 'position'): Collection
    {
        $results = [];
        $items = collect($items)->sortBy($sortBy);

        foreach ($items as $item) {
            if ($children = Arr::get($item, 'children')) {
                $item['permissions'] = array_merge(
                    collect($children)->pluck('permissions')->unique()->toArray(),
                    $item['permissions'] ?? []
                );
            }

            $item = new static($item);
            $results[] = $item;
        }

        return (new Collection($results))->filter(fn ($item) => !empty($item));
    }

    public function __construct($item)
    {
        $this->item = new Collection($item);
    }

    public function hasChildren(): bool
    {
        if ($this->item->has('children')) {
            return count($this->item->get('children')) > 0;
        }

        return false;
    }

    public function get($key, $default = null)
    {
        return $this->item->get($key, $default);
    }

    public function getUrl(): string
    {
        $url = $this->get('url');
        if ($url == 'dashboard') {
            return '';
        }

        return '/' . $url;
    }

    public function getChildrens(): array|Collection
    {
        return static::make($this->item->get('children', []));
    }

    public function toArray(): array
    {
        $user = auth()->user();

        if (!$user->canAny($this->get('permissions', ['admin']))) {
            return [];
        }

        $adminPrefix = config('juzaweb.admin_prefix');
        $segment3 = request()?->segment(3);
        $segment2 = request()?->segment(2);

        $item = $this->item->toArray();
        $item['url'] = admin_url($this->getUrl());
        $item['active'] = $this->get('url') == 'dashboard'
            ? request()?->is($adminPrefix)
            : request()?->is($adminPrefix .'/'. $this->get('url') . '*');

        if ($this->hasChildren()) {
            $children = $this->getChildrens();

            foreach ($children as $child) {
                if (empty($segment2)) {
                    $active = empty($child->getUrl());
                } else {
                    $active = request()?->is($adminPrefix .'/'. $child->get('url') . '*');
                }

                if ($active) {
                    $item['active'] = true;
                }
            }

            $item['children'] = $children->toArray();
        }

        return $item;
    }
}
