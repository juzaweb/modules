<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Backend\Http\Datatables\PostType;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Juzaweb\Backend\Models\Taxonomy;
use Juzaweb\Backend\Repositories\TaxonomyRepository;
use Juzaweb\CMS\Abstracts\DataTable;

class TaxonomyDataTable extends DataTable
{
    protected array $taxonomy;

    public function __construct(
        protected TaxonomyRepository $taxonomyRepository
    ) {
    }

    public function mount($taxonomy): void
    {
        $this->taxonomy = $taxonomy;
    }

    public function columns(): array
    {
        $columns = [
            'name' => [
                'label' => trans('cms::app.name'),
                'formatter' => [$this, 'rowActionsFormatter'],
            ]
        ];

        if (in_array('hierarchical', Arr::get($this->taxonomy, 'supports', []))) {
            $columns['parent'] = [
                'label' => trans('cms::app.parent'),
                'width' => '20%',
                'align' => 'center',
                'formatter' => function ($value, $row, $index) {
                    return $row->parent->name ?? '__';
                }
            ];
        }

        $columns['total_post'] = [
            'label' => trans('cms::app.total_posts'),
            'width' => '15%',
            'align' => 'center',
        ];

        $columns['created_at'] = [
            'label' => trans('cms::app.created_at'),
            'width' => '15%',
            'align' => 'center',
            'formatter' => function ($value, $row, $index) {
                return jw_date_format($row->created_at);
            },
        ];

        return $columns;
    }

    /**
     * Query data datatable
     *
     * @param  array  $data
     * @return Builder
     */
    public function query(array $data): Builder
    {
        $data['taxonomy'] = $this->taxonomy['taxonomy'];

        if ($this->taxonomy['taxonomy'] != 'tags') {
            $data['post_type'] = $this->taxonomy['post_type'];
        }

        $sort = [
            'sort_order' => Arr::get($data, 'order', 'desc'),
            'sort_by' => Arr::get($data, 'sort', 'id')
        ];

        return $this->taxonomyRepository
            ->with(['parent'])
            ->withSearchs(Arr::get($data, 'keyword'))
            ->withFilters($data)
            ->withSorts($sort)
            ->getQuery();
    }

    public function rowAction(mixed $row): array
    {
        $data = parent::rowAction($row);

        $data['view'] = [
            'label' => trans('cms::app.view'),
            'url' => $row->getLink(),
            'target' => '_blank',
        ];

        return $data;
    }

    public function bulkActions(string $action, array $ids): void
    {
        foreach ($ids as $id) {
            DB::beginTransaction();
            try {
                if ($action == 'delete') {
                    $model = $this->makeModel()->find($id);
                    $model->delete();
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }

    protected function makeModel()
    {
        return app(Taxonomy::class);
    }
}
