<?php

namespace Juzaweb\CMS\Support\Element\Contracts;

use Juzaweb\CMS\Support\Element\Interfaces\Element;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;

interface ElementBuilder extends Arrayable, Renderable
{
    public function elementByType(string $element, array $configs = []): Element;
}
