<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Support\Element\Elements;

use Juzaweb\CMS\Support\Element\Interfaces\Element;
use Juzaweb\CMS\Support\Element\Interfaces\WithChildren;
use Juzaweb\CMS\Support\Element\Traits;

class Card implements Element, WithChildren
{
    use Traits\HasClass, Traits\HasChildren, Traits\HasId;

    protected string $element = 'card';

    protected string $class = 'card';

    protected string $title;

    protected string $headerClassName;

    protected string $titleClassName;

    public function __construct(array $configs = [])
    {
        foreach ($configs as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function headerClass(string $class): static
    {
        $this->headerClassName = $class;

        return $this;
    }

    public function titleClass(string $class): static
    {
        $this->titleClassName = $class;

        return $this;
    }

    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function toArray(): array
    {
        $data = get_object_vars($this);
        $data['className'] = $this->class;
        $data['children'] = $this->getChildren()->toArray();
        return $data;
    }

    public function render(): string
    {
        return '';
    }
}
