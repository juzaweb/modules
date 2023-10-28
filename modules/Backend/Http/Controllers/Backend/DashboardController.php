<?php

namespace Juzaweb\Backend\Http\Controllers\Backend;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Inertia\Response;
use Juzaweb\Backend\Models\MediaFile;
use Juzaweb\Backend\Models\Post;
use Juzaweb\Backend\Models\PostView;
use Juzaweb\CMS\Abstracts\Action;
use Juzaweb\CMS\Http\Controllers\BackendController;
use Juzaweb\CMS\Models\User;
use Juzaweb\CMS\Support\Element\Contracts\ElementBuilder;

class DashboardController extends BackendController
{
    protected string $template = 'inertia';

    public function index(): View|Response
    {
        do_action(Action::BACKEND_DASHBOARD_ACTION);

        $title = trans('cms::app.dashboard');
        $builder = app()->make(ElementBuilder::class);
        $this->buildStatistics($builder);
        $this->buildTopViewsChart($builder);
        $this->buildViewsTable($builder);

        return $this->view(
            'cms::backend.builder',
            compact(
                'title',
                'builder'
            )
        );
    }

    protected function buildStatistics(ElementBuilder $builder): void
    {
        $users = User::count();
        $posts = Post::where('type', '!=', 'pages')
            ->wherePublish()
            ->count();
        $pages = Post::where('type', '=', 'pages')
            ->wherePublish()
            ->count();
        $storage = format_size_units(MediaFile::sum('size'));
        $diskFree = Cache::store('file')->remember(
            cache_prefix('storage_free_disk'),
            3600,
            fn () => format_size_units(disk_free_space('/')),
        );

        $row = $builder->row()->addClass('mt-3');
        $cols = [
            [
                'title' => trans('cms::app.posts'),
                'data' => trans('cms::app.total').": {$posts}",
                'class' => 'border-0 bg-gray-2',
            ],
            [
                'title' => trans('cms::app.pages'),
                'data' => trans('cms::app.total').": {$pages}",
                'class' => 'border-0 bg-info text-white',
            ],
            [
                'title' => trans('cms::app.users'),
                'data' => trans('cms::app.total').": {$users}",
                'class' => 'border-0 bg-primary text-white',
            ],
            [
                'title' => trans('cms::app.storage'),
                'data' => "{$storage}/{$diskFree}",
                'class' => 'border-0 bg-success text-white',
            ]
        ];

        foreach ($cols as $col) {
            $row->col(['cols' => 3])
                ->statsCard()
                ->title($col['title'])
                ->data($col['data'])
                ->addClass($col['class']);
        }
    }

    protected function buildTopViewsChart(ElementBuilder $builder): void
    {
        $today = Carbon::today();
        $minDay = $today->subDays(7);
        $labels = [];

        for ($i = 1; $i <= 7; $i++) {
            $day = $minDay->addDay();
            $labels[] = $day->format('Y-m-d');
        }

        $builder->row()->addClass('mt-5')->col(['cols' => 12])->lineChart(
            [
                'labels' => $labels,
                'dataUrl' => action([static::class, 'viewsChart']),
            ]
        );
    }

    protected function buildViewsTable(ElementBuilder $builder): void
    {
        $row = $builder->row()->addClass('mt-5');
        $row->col(['cols' => 6])
            ->card()
            ->headerClass('bg-primary')
            ->titleClass('text-white')
            ->title(trans('cms::app.new_users'))
            ->dataTable()
            ->columns(
                [
                    [
                        'key' => 'name',
                        'label' => trans('cms::app.name'),
                    ],
                    [
                        'key' => 'created',
                        'label' => trans('cms::app.created_at'),
                    ]
                ]
            )
            ->dataUrl(action([static::class, 'getDataUser']))
            ->perPage(10);

        $row->col(['cols' => 6])
            ->card()
            ->headerClass('bg-primary')
            ->titleClass('text-white')
            ->title(trans('cms::app.top_views'))
            ->dataTable()
            ->columns(
                [
                    [
                        'key' => 'title',
                        'label' => trans('cms::app.title'),
                    ],
                    [
                        'key' => 'views',
                        'label' => trans('cms::app.views'),
                    ]
                ]
            )
            ->dataUrl(action([static::class, 'getDataTopViews']))
            ->perPage(10);
    }

    public function getDataUser(Request $request): JsonResponse
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = User::query();
        $query->where('status', '=', User::STATUS_ACTIVE);
        $query->where('is_admin', '=', 0);

        $query->orderBy('created_at', 'DESC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get(
            [
                'id',
                'name',
                'email',
                'created_at',
            ]
        );

        foreach ($rows as $row) {
            $row->created = jw_date_format($row->created_at);
        }

        return response()->json(
            [
                'total' => count($rows),
                'rows' => $rows,
            ]
        );
    }

    public function getDataTopViews(Request $request): JsonResponse
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $result = Cache::store('file')->remember(
            cache_prefix('data_top_views'),
            3600,
            function () use ($offset, $limit) {
                $query = Post::query();
                $query->wherePublish();

                $query->orderBy('views', 'DESC');
                $query->offset($offset);
                $query->limit($limit);

                $rows = $query->get(
                    [
                        'id',
                        'title',
                        'views',
                        'created_at',
                    ]
                );

                foreach ($rows as $row) {
                    $row->created = jw_date_format($row->created_at);
                    $row->views = number_format($row->views);
                }

                return [
                    'total' => count($rows),
                    'rows' => $rows,
                ];
            }
        );

        return response()->json($result);
    }

    public function viewsChart(): JsonResponse
    {
        $result = Cache::store('file')->remember(
            cache_prefix('views_chart'),
            3600,
            function () {
                $result = [];
                $today = Carbon::today();
                $minDay = $today->subDays(7);

                $result[] = [
                    'label' => __('Page Views'),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
                ];

                $result[] = [
                    'label' => __('New Users'),
                    'backgroundColor' => 'rgba(53, 162, 235, 0.5)',
                ];

                for ($i = 1; $i <= 7; $i++) {
                    $day = $minDay->addDay();
                    $result[0]['data'][] = $this->countViewByDay($day->format('Y-m-d'));
                    $result[1]['data'][] = $this->countUserByDay($day->format('Y-m-d'));
                }

                return $result;
            }
        );

        return response()->json($result);
    }

    protected function countViewByDay(string $day): int
    {
        return PostView::where('day', '=', $day)->sum('views');
    }

    protected function countUserByDay(string $day): int
    {
        return User::whereDate('created_at', '=', $day)->count('id');
    }

    public function removeMessage(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate(
            [
                'id' => 'required'
            ],
            [],
            [
                'id' => trans('Message ID')
            ]
        );

        remove_backend_message($request->input('id'));

        return $this->success(
            [
                'message' => trans('cms::app.successfully')
            ]
        );
    }
}
