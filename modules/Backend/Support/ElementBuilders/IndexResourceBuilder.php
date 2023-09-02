<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Backend\Support\ElementBuilders;

use Juzaweb\CMS\Interfaces\ElementBuilders\ElementBuilder as ElementBuilderInterface;
use Juzaweb\CMS\Abstracts\ElementBuilders\ElementBuilder;

class IndexResourceBuilder extends ElementBuilder implements ElementBuilderInterface
{
    public function __construct(protected array $params = [])
    {
        //
    }

    public function toArray(): array
    {
        $builder = $this->builder();
        $builder->row()
            ->col(['cols' => 12])
            ->buttonGroup()->addClass('float-right')
            ->link(['href' => $this->params['linkCreate']])
            ->text(trans('cms::app.add_new'))
            ->addClass('btn btn-success');

        $builder->row()
            ->col(['cols' => 12])
            ->dataTable($this->params['dataTable']);

        return $builder->toArray();
    }
}
