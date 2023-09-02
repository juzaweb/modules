<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Support\Element\Elements\Buttons;

use Illuminate\Support\Arr;
use Juzaweb\CMS\Support\Element\Interfaces\Element;
use Juzaweb\CMS\Support\Element\Traits\HasChildren;
use Juzaweb\CMS\Support\Element\Traits\HasClass;

class ButtonGroup implements Element
{
    use HasClass, HasChildren;

    protected string $element = 'button-group';

    protected string $class = 'btn-group';

    public function toArray(): array
    {
        $data = Arr::except(get_object_vars($this), ['class', 'children']);

        $data['className'] = $this->class;

        $data['children'] = $this->getChildren()->toArray();

        return $data;
    }

    public function render(): string
    {
        return '';
    }
}