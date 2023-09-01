<?php

namespace Juzaweb\CMS\Support\Element\Traits;

use Illuminate\Support\Str;

trait HasId
{
    protected ?string $id;

    public function id(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
