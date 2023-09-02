<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Support\Element\Elements\Charts;

use Juzaweb\CMS\Support\Element\Interfaces\Element;

abstract class Chart implements Element
{
    protected ?string $class = null;

    protected ?string $title = null;

    protected array $labels = [];

    protected array $data = [];

    protected string $dataUrl;

    public function __construct(array $configs = [])
    {
        foreach ($configs as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function data(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function dataFromUrl(string $url): static
    {
        $this->dataUrl = $url;

        return $this;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function render(): string
    {
        return '';
    }
}
