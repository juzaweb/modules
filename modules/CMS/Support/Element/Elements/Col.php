<?php

namespace Juzaweb\CMS\Support\Element\Elements;

use Juzaweb\CMS\Support\Element\Interfaces\Element;
use Juzaweb\CMS\Support\Element\Traits\HasChildren;
use Juzaweb\CMS\Support\Element\Traits\HasClass;
use Juzaweb\CMS\Support\Element\Traits\HasId;

class Col implements Element
{
    use HasClass, HasChildren, HasId;

    protected string $class = 'col';

    protected int $cols = 12;

    protected string $size = 'md';

    public function __construct(array $configs = [])
    {
        foreach ($configs as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function cols(int $cols): static
    {
        $this->cols = $cols;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'element' => 'col',
            'class' => "{$this->class} col-{$this->size}-{$this->cols}",
            'id' => $this->getId(),
            'children' => $this->getChildren()->toArray(),
        ];
    }

    public function render(): string
    {
        return view('element::col', $this->toArray())->render();
    }
}
