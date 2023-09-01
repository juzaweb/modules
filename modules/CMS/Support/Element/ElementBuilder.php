<?php

namespace Juzaweb\CMS\Support\Element;

use Juzaweb\CMS\Support\Element\Contracts\ElementBuilder as ElementBuilderContract;
use Juzaweb\CMS\Support\Element\Elements\Form;
use Juzaweb\CMS\Support\Element\Interfaces\Element;

class ElementBuilder implements ElementBuilderContract
{
    use Traits\HasChildren;

    public function toArray(): array
    {
        return [
            'children' => $this->children->toArray(),
        ];
    }

    public function form(array $configs = []): Form
    {
        $form = new Form($configs);
        $this->pushChild($form);
        return $form;
    }

    public function elementByType(string $element, array $configs = []): Element
    {
        return match ($element) {
            'form' => new Form($configs),
            'input' => new Inputs\Input($configs),
            'textarea' => new Inputs\Textarea($configs),
            'row' => new Elements\Row($configs),
            default => throw new \Exception("Element type {$element} not found"),
        };
    }

    public function render(): string
    {
        return view('inputs::input', $this->toArray())->render();
    }
}
