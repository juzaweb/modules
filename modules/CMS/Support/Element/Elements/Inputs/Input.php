<?php

namespace Juzaweb\CMS\Support\Element\Elements\Inputs;

use Juzaweb\CMS\Support\Element\Abstracts\InputAbstract;

class Input extends InputAbstract
{
    protected string $type = 'text';

    protected string $element = 'input';

    protected ?int $max;
    protected ?int $min;

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function render(): string
    {
        return view('inputs::input', $this->toArray())->render();
    }
}
