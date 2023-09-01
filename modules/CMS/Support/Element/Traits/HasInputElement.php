<?php

namespace Juzaweb\CMS\Support\Element\Traits;

use Juzaweb\CMS\Support\Element\Inputs\Input;
use Juzaweb\CMS\Support\Element\Inputs\Textarea;

trait HasInputElement
{
    public function input(?string $label = null, ?string $name = null, array $options = []): Input
    {
        $input = new Input(['label' => $label, 'name' => $name, ...$this->parseOptions($options)]);

        $this->pushChild($input);

        return $input;
    }

    public function textarea(?string $label = null, ?string $name = null, array $options = []): Textarea
    {
        $input = new Textarea(['label' => $label, 'name' => $name, ...$this->parseOptions($options)]);

        $this->children->pushChild($input);

        return $input;
    }

    protected function parseOptions(array $options): array
    {
        return $options;
    }
}
