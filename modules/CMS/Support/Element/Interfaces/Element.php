<?php

namespace Juzaweb\CMS\Support\Element\Interfaces;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;

interface Element extends Arrayable, Renderable
{
    public function render(): string;
}
