<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://github.com/juzaweb/cms
 * @license    GNU V2
 *
 * Created by JUZAWEB.
 * Date: 5/31/2021
 * Time: 9:55 PM
 */

namespace Juzaweb\CMS\Abstracts;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Database\Query\Builder;

abstract class DataTable extends BaseDataTable
{

    /**
     * Columns datatable
     *
     * @return array
     */
    abstract public function columns(): array;

    /**
     * Query data datatable
     *
     * @param  array  $data
     * @return Builder
     */
    abstract public function query(array $data): Builder;

    /**
     * Renders the view for the PHP function.
     *
     * @return Factory|View
     */
    public function render(): Factory|View
    {
        if (empty($this->currentUrl)) {
            $this->currentUrl = url()->current();
        }

        return view(
            'cms::components.datatable',
            $this->getDataRender()
        );
    }
}
