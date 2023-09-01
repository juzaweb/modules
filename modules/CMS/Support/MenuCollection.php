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

        $item = $this->item->toArray();
        $item['url'] = admin_url($this->getUrl());

        if ($this->hasChildren()) {
            $item['children'] = $this->getChildrens()->toArray();
        }

        return $item;
    }
}
