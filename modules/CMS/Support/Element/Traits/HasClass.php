<?php

namespace Juzaweb\CMS\Support\Element\Traits;

trait HasClass
{
    protected string $class;

    public function class(string $class): static
    {
        $this->class = $class;

        return $this;
    }

    public function addClass(string|array $class): static
    {
        if (is_array($class)) {
            $class = implode(' ', $class);
        }

        $this->class .= ' ' . trim($class);

        return $this;
    }
}
