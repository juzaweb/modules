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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Juzaweb\Backend\Repositories\PostRepository;
use Juzaweb\CMS\Abstracts\DataTable;
use Juzaweb\CMS\Facades\HookAction;

class PostTypeDataTable extends DataTable
{
    protected array $postType;

    protected ?Collection $resourses = null;

    protected ?Collection $taxonomies = null;

    public function __construct(
        protected PostRepository $postRepository
    ) {
    }

    public function mount($postType): void
    {
        if (is_string($postType)) {
            $postType = HookAction::getPostTypes($postType)->toArray();
        }

        $this->postType = $postType;
        $this->taxonomies = HookAction::getTaxonomies($this->postType);

        $resourses = HookAction::getResource()->where('post_type', $postType['key'])->whereNull('parent');
        if ($resourses->isNotEmpty()) {
            $this->resourses = $resourses;
        }
    }

    public function columns(): array
    {
        if ($this->postType['key'] != 'pages') {
            $columns['thumbnail'] = [
                'label' => trans('cms::app.thumbnail'),
                'width' => '10%',
                'sortable' => false,
                'align' => 'center',
                'formatter' => function ($value, $row, $index) {
                    return '<img class="lazyload w-100 img-thumbnail" data-src="'. $row->getThumbnail('150xauto') .'"'
                        .' src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="/>';
                },
            ];
        }

        $columns['title'] = [
            'label' => trans('cms::app.title'),
            'formatter' => [$this, 'rowActionsFormatter'],
        ];

        $taxonomies = $this->taxonomies->take(3);

        foreach ($taxonomies as $key => $taxonomy) {
            $columns["tax_{$key}"] = [
                'label' => $taxonomy->get('label'),
                'width' => '15%',
                'sortable' => false,
                'formatter' => function ($value, $row, $index) use ($key) {
                    return $row->taxonomies
                        ->where('taxonomy', '=', $key)
                        ->take(5)
                        ->pluck('name')
                        ->join(', ');
                }
            ];
        }

        $columns['created_at'] = [
            'label' => trans('cms::app.created_at'),
            'width' => '10%',
            'formatter' => function ($value, $row, $index) {
                return jw_date_format($row->created_at);
            },
        ];

        $columns['status'] = [
            'label' => trans('cms::app.status'),
            'width' => '10%',
            'align' => 'center',
            'formatter' => function ($value, $row, $index) {
                return view(
                    'cms::components.datatable.status',
                    compact(
                        'row'
                    )
                )->render();
            },
        ];

        return $columns;
    }

    public function actions(): array
    {
        $statuses = $this->makeModel()->getStatuses();

        $statuses['delete'] = trans('cms::app.delete');

        return $statuses;
    }

    public function bulkActions(string $action, array $ids): void
    {
        $statuses = array_keys($this->makeModel()->getStatuses());
        $posts = $this->makeModel()->whereIn('id', $ids)->get();

        foreach ($posts as $post) {
            DB::beginTransaction();
            try {
                if ($action == 'delete') {
                    $post->delete();
                }

                if (in_array($action, $statuses)) {
                    $post->update(
                        [
                            'status' => $action,
                        ]
                    );
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }

    public function searchFields(): array
    {
        $data = [
            'keyword' => [
                'type' => 'text',
                'label' => trans('cms::app.keyword'),
                'placeholder' => trans('cms::app.keyword'),
            ],
            'status' => [
                'type' => 'select',
                'width' => '100px',
                'label' => trans('cms::app.status'),
                'options' => $this->makeModel()->getStatuses(),
            ],
            // 'locale' => [
            //     'type' => 'select',
            //     'width' => '100px',
            //     'label' => trans('cms::app.language'),
            //     'options' => Language::cacheFor(3600)->pluck('name', 'code')->all(),
            // ],
        ];

        $taxonomies = HookAction::getTaxonomies($this->postType['key']);
        foreach ($taxonomies as $key => $taxonomy) {
            $data[$key] = [
                'type' => 'taxonomy',
                'label' => $taxonomy->get('label'),
                'taxonomy' => $taxonomy,
            ];
        }

        return $data;
    }

    public function rowAction(mixed $row): array
    {
        $data = parent::rowAction($row);

        if ($this->resourses) {
            foreach ($this->resourses as $key => $resourse) {
                $data["resource_{$key}_action"] = [
                    'label' => $resourse->get('label_action'),
                    'priority' => 10,
                    'url' => route(
                        'admin.post_resource.index',
                        [
                            $key,
                            $row->id
                        ]
                    ),
                ];
            }
        }

        if (in_array($row->status, ['publish', 'private'])) {
            $data['view'] = [
                'label' => trans('cms::app.view'),
                'url' => $row->getLink(),
                'target' => '_blank',
                'priority' => 1,
            ];
        }

        return collect($data)->sortBy('priority')->all();
    }

    public function query(array $data): Builder
    {
        $data['type'] = $this->postType['key'];

        $sort = [
            'sort_order' => Arr::get($data, 'order', 'desc'),
            'sort_by' => Arr::get($data, 'sort', 'id'),
        ];

        return $this->postRepository
            ->withSearchs(Arr::get($data, 'keyword'))
            ->withFilters($data)
            ->withSorts($sort)
            ->scopeQuery(
                fn ($q) => $q->when(empty($data['status']), fn ($q2) => $q2->where('status', '!=', 'trash'))
            )
            ->getQuery()
            ->with(['taxonomies']);
    }

    public function rowActionsFormatter(mixed $value, mixed $row, int $index): string
    {
        return view(
            'cms::backend.items.datatable_item',
            [
                'value' => $row->{$row->getFieldName()},
                'row' => $row,
                'actions' => $this->rowAction($row),
                'editUrl' => $this->currentUrl .'/'. $row->id . '/edit',
            ]
        )
            ->render();
    }

    public function titleDetailFormater($index, $row, $taxonomies, $postTypeTaxonomies): string
    {
        return view(
            'cms::backend.items.quick_edit',
            compact('index', 'row', 'taxonomies', 'postTypeTaxonomies')
        )->render();
    }

    protected function makeModel()
    {
        return app($this->postType['model']);
    }
}
