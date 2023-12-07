<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Backend\Http\Datatables;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Juzaweb\CMS\Abstracts\DataTable;
use Juzaweb\CMS\Facades\Plugin;

class PluginDatatable extends DataTable
{
    public function columns(): array
    {
        // TODO: Implement columns() method.
    }

    public function query(array $data): Builder
    {
        // TODO: Implement query() method.
    }

    public function getData(Request $request): array
    {
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = (int) $request->get('limit', 20);
        $page = round(($offset + $limit) / $limit);

        $rows = collect(apply_filters('admin.plugins.all', Plugin::all()));

        return [$count, $rows];
    }
}
