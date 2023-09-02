<?php

namespace Juzaweb\CMS\Support\Element\Abstracts;

use Illuminate\Support\Arr;
use Juzaweb\CMS\Support\Element\Interfaces\Element;

abstract class ElementAbstract implements Element
{
    public function toArray(): array
    {
        $data = Arr::except(get_object_vars($this), ['class', 'children']);

        $data['className'] = $this->class ?? null;

        $data['children'] = $this->getChildren()->toArray();

        return $data;
    }
}
