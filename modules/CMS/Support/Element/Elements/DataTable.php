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

use Illuminate\Support\Arr;
use Juzaweb\CMS\Abstracts\BaseDataTable;
use Juzaweb\CMS\Support\Element\Interfaces\Element;
use Juzaweb\CMS\Support\Element\Traits\HasClass;
use Juzaweb\CMS\Support\Element\Traits\HasId;

class DataTable extends BaseDataTable implements Element
{
    use HasClass, HasId;

    protected string $element = 'data-table';

    protected string $class = 'table data-table';

    public function __construct(array $configs)
    {
        foreach ($configs as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function toArray(): array
    {
        $parent = parent::toArray();

        return array_merge($parent, Arr::only(get_object_vars($this), ['class', 'element', 'id']));
    }

    public function render(): string
    {
        return '';
    }
}
