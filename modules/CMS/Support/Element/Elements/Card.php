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
    use Traits\HasClass, Traits\HasChildren, Traits\HasName, Traits\HasId;

    protected string $class = 'card';

    public function toArray(): array
    {
        return [
            'className' => $this->class,
            'children' => $this->getChildren()->toArray(),
        ];
    }

    public function render(): string
    {
        return '';
    }
}
