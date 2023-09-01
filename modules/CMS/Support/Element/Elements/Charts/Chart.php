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

class Chart implements Element
{
    protected ?string $class = null;

    protected string $element = 'chart-line';

    protected string $title = '';

    protected array $labels = [];

    protected array $data = [];

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

    public function toArray(): array
    {
        return [
            'element' => $this->element,
            'title' => $this->title,
            'data' => $this->data,
            'labels' => $this->labels,
        ];
    }

    public function render(): string
    {
        return '';
    }
}
