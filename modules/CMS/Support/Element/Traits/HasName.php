<?php

namespace Juzaweb\CMS\Support\Element\Traits;

trait HasName
{
    protected ?string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
