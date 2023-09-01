<?php

namespace Juzaweb\CMS\Support\Element\Elements;

use Juzaweb\CMS\Support\Element\Interfaces\Element;
use Juzaweb\CMS\Support\Element\Traits\HasChildren;
use Juzaweb\CMS\Support\Element\Traits\HasClass;
use Juzaweb\CMS\Support\Element\Traits\HasId;

class Col implements Element
{
    use HasClass, HasChildren, HasId;

    public function __construct(array $configs = [])
    {
        foreach ($configs as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function toArray(): array
    {
        return [
            'class' => $this->class,
            'id' => $this->getId(),
            'children' => $this->children,
        ];
    }

    public function render(): string
    {
        return view('element::col', $this->toArray())->render();
    }
}
