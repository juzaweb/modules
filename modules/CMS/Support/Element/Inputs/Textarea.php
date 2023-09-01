<?php

namespace Juzaweb\CMS\Support\Element\Inputs;

use Juzaweb\CMS\Support\Element\Abstracts\InputAbstract;

class Textarea extends InputAbstract
{
    protected string $element = 'textarea';

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     * @throws \Throwable
     */
    public function render(): string
    {
        return view('inputs::input', $this->toArray())->render();
    }
}
