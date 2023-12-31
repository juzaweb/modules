<?php

namespace Juzaweb\CMS\Support\Element\Elements;

use Juzaweb\CMS\Support\Element\Interfaces\Element;
use Juzaweb\CMS\Support\Element\Traits\HasChildren;
use Juzaweb\CMS\Support\Element\Traits\HasClass;
use Juzaweb\CMS\Support\Element\Traits\HasId;

class Row implements Element
{
    use HasClass, HasChildren, HasId;

    protected string $class = 'row';

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
            'element' => 'row',
            'id' => $this->getId(),
            'className' => $this->class,
            'children' => $this->getChildren()->toArray(),
        ];
    }

    public function render(): string
    {
        return view('element::row', $this->toArray())->render();
    }
}
