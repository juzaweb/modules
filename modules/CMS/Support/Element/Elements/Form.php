<?php

namespace Juzaweb\CMS\Support\Element\Elements;

use Juzaweb\CMS\Support\Element\Contracts\ElementBuilder;
use Juzaweb\CMS\Support\Element\Interfaces\Element;
use Juzaweb\CMS\Support\Element\Interfaces\WithChildren;
use Juzaweb\CMS\Support\Element\Traits;
use Illuminate\Support\Str;

class Form implements Element, WithChildren
{
    use Traits\HasClass, Traits\HasChildren, Traits\HasName, Traits\HasId;

    protected string $action;
    protected string $method = 'POST';
    protected string $element = 'form';

    protected string $enctype;

    protected string $target;

    protected string $acceptCharset;

    protected string $autocomplete;

    protected string $novalidate;

    public function __construct(array $configs = [])
    {
        foreach ($configs as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function action(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function method(string $method): static
    {
        $this->method = $method;

        return $this;
    }

    public function createFields(string|array $fields): static
    {
        if (is_string($fields)) {
            $fields = collect(explode(',', $fields))->map(
                fn ($field) => $this->createFieldFromString($field)
            )->toArray();
        }

        collect($fields)->map(fn ($field) => $this->createField($field));

        return $this;
    }

    public function createField(string|array $field): static
    {
        if (is_string($field)) {
            $name = Str::slug($field, '_');
            $field = [
                'type' => 'text',
                'element' => 'input',
                'name' => $name,
                'label' => $field,
                'id' => "_{$name}",
            ];
        }

        $this->pushChild($field);

        return $this;
    }

    public function toArray(): array
    {
        $atributes = get_object_vars($this);
        $atributes['children'] = $this->getChildren()->toArray();
        return $atributes;
    }

    public function render(): string
    {
        return view('element::form', $this->toArray())->render();
    }

    protected function createFieldFromString(string $field): Element
    {
        $name = explode('|', $field)[0];
        $type = explode('|', $field)[1] ?? 'text';
        // replace not string to whitespaces
        $label = preg_replace('/[^a-zA-Z0-9]+/', ' ', trim($name));
        $label = Str::replace('_', ' ', $label);

        return $this->makeElementBuilder()->elementByType(
            $type === 'textarea' ? 'textarea' : 'input',
            [
                'type' => $type,
                'name' => Str::slug(trim($name), '_'),
                'label' => $label,
            ]
        );
    }

    protected function makeElementBuilder(): ElementBuilder
    {
        return app()->make(ElementBuilder::class);
    }
}
