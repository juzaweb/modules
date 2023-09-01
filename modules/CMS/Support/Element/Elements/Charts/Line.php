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

class Line implements Element
{
    protected ?string $class = null;

    protected string $element = 'chart-line';

    protected string $title = '';

    protected array $data = [];

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function toArray(): array
    {
        return [
            'element' => $this->element,
        ];
    }

    public function render(): string
    {
        // TODO: Implement render() method.
    }
}
