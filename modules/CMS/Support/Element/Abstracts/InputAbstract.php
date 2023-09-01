<?php

namespace Juzaweb\CMS\Support\Element\Abstracts;

use Juzaweb\CMS\Support\Element\Traits\HasClass;
use Juzaweb\CMS\Support\Element\Traits\HasId;
use Juzaweb\CMS\Support\Element\Traits\HasLabel;
use Juzaweb\CMS\Support\Element\Traits\HasName;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class InputAbstract extends ElementAbstract
{
    use HasClass, HasId, HasLabel, HasName;

    protected bool $required = false;
    protected bool $disabled = false;
    protected bool $readonly = false;

    protected ?string $placeholder;
    protected ?int $maxLength;
    protected ?int $minLength;

    protected null|string|array $value;

    public function __construct(array $configs = [])
    {
        foreach ($configs as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function getId(): string
    {
        return $this->id ?? '_' . Str::slug($this->name, '_');
    }

    public function maxLength(int $maxLength): static
    {
        $this->maxLength = $maxLength;

        return $this;
    }

    public function minLength(int $minLength): static
    {
        $this->minLength = $minLength;

        return $this;
    }

    public function required(bool $required = true): static
    {
        $this->required = $required;

        return $this;
    }

    public function disabled(bool $disabled): static
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function readonly(bool $readonly = true): static
    {
        $this->readonly = $readonly;

        return $this;
    }

    public function value(string|int|float|null|array $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function placeholder(string $placeholder): static
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function toArray(): array
    {
        $vars = get_object_vars($this);
        $array = Arr::only($vars, ['name', 'label', 'type', 'element']);
        $array['options'] = Arr::except($vars, ['name', 'label', 'type', 'element']);
        $array['options']['id'] = $this->getId();
        return $array;
    }
}
