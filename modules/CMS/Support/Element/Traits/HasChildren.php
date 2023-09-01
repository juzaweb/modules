<?php

namespace Juzaweb\CMS\Support\Element\Traits;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

trait HasChildren
{
    use HasInputElement;

    protected Collection $children;

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function setChildren(Collection $children): static
    {
        $this->children = $children;

        return $this;
    }

    public function pushChild(array|Arrayable $child): static
    {
        if (!isset($this->children)) {
            $this->children = new Collection();
        }

        $this->children->push($child);

        return $this;
    }
}
