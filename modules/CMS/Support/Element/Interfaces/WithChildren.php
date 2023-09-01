<?php

namespace Juzaweb\CMS\Support\Element\Interfaces;

use Illuminate\Support\Collection;

interface WithChildren
{
    public function getChildren(): Collection;

    public function setChildren(Collection $children): static;
}
