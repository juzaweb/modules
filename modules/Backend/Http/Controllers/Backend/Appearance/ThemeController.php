<?php

namespace Juzaweb\Backend\Http\Controllers\Backend\Appearance;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Response;
use Juzaweb\Backend\Events\ThemeActivateSuccess;
use Juzaweb\Backend\Http\Requests\Appearance\Theme\ActivateRequest;
use Juzaweb\CMS\Contracts\BackendMessageContract;
use Juzaweb\CMS\Contracts\JuzawebApiContract;
use Juzaweb\CMS\Facades\CacheGroup;
use Juzaweb\CMS\Facades\Theme;
use Juzaweb\CMS\Facades\ThemeLoader;
use Juzaweb\CMS\Http\Controllers\BackendController;
use Juzaweb\CMS\Support\ArrayPagination;
use Juzaweb\CMS\Version;
use Juzaweb\Network\Facades\Network;
use Throwable;

class ThemeController extends BackendController
{
    protected JuzawebApiContract $api;
    protected BackendMessageContract $message;

    //protected string $template = 'inertia';

    public function __construct(JuzawebApiContract $api, BackendMessageContract $message)
    {
        $this->api = $api;
        $this->message = $message;
    }

    public function index(): View|Response
    {
        global $jw_user;

        if (!$jw_user->can('themes.index')) {
            abort(403);
        }

        $activated = jw_current_theme();
        $currentTheme = ThemeLoader::getThemeInfo($activated);

        return $this->view(
            'cms::backend.theme.index',
            [
                'title' => trans('cms::app.themes'),
                'currentTheme' => $currentTheme,
                'activated' => $activated,
            ]
        );
    }

    public function getDataTheme(Request $request): JsonResponse
    {
        if (!$request->user()->can('themes.index')) {
            abort(403);
        }

        $limit = $request->get('limit', 20);
        $network = $request->get('network');

        $activated = jw_current_theme();

        /** @var Collection $themes */
        $themes = apply_filters('admin.themes.all', app('themes')->all(true))
            ->where('name', '!=', $activated);

        if (config('network.enable') && Network::isSubSite()) {
            $themes = $themes->where('networkable', true);
        }

        $paginate = ArrayPagination::make($themes);

        $paginate = $paginate->paginate($limit);
        //$updates = $this->getDataUpdates($paginate->getCollection());

        $items = new Collection();
        foreach ($paginate->items() as $theme) {
            $theme['update'] = false;

            $items->push(
                (object) [
                    'update' => $theme['update'],
                    'name' => $theme['name'],
                    'content' => view(
                        'cms::backend.theme.components.theme_item',
                        [
                            'theme' => (object) $theme,
                            'network' => $network
                        ]
                    )->render(),
                ]
            );
        }

        $paginate->setCollection($items);

        return response()->json(
            [
                'data' => $paginate->items(),
                'meta' => [
                    'totalPages' => $paginate->lastPage(),
                    'limit' => $paginate->perPage(),
                    'total' => $paginate->total(),
                    'page' => $paginate->currentPage(),
                ]
            ]
        );
    }

    public function activate(ActivateRequest $request): JsonResponse
    {
        if (!$request->user()->can('themes.edit')) {
            abort(403);
        }

        $name = $request->post('theme');
        if (!$theme = Theme::find($name)) {
            return $this->error(
                [
                    'message' => trans('cms::message.theme_not_found'),
                ]
            );
        }

        $canActive = true;
        if (config('network.enable') && Network::isSubSite()) {
            $canActive = $theme->isNetworkSupport();
        }

        $canActive = apply_filters('admin.themes.can_active', $canActive, $theme);

        if (!$canActive) {
            return $this->error(trans('You do not have permission to activate this theme.'));
        }

        DB::beginTransaction();
        try {
            $theme->activate();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return $this->error($e->getMessage());
        }

        event(new ThemeActivateSuccess($theme));

        return $this->success(
            [
                'redirect' => route('admin.themes'),
            ]
        );
    }

    public function bulkActions(Request $request): JsonResponse|RedirectResponse
    {
        if (!config('juzaweb.theme.enable_upload')) {
            abort(403);
        }

        if (!$request->user()->can('themes.edit')) {
            abort(403);
        }

        $action = $request->post('action');
        $ids = $request->post('ids', []);

        if ($action == 'update') {
            $query = ['themes' => $ids];
            $query = http_build_query($query);

            return $this->success(
                [
                    'window_redirect' => route('admin.update.process', ['theme']).'?'.$query,
                ]
            );
        }

        foreach ($ids as $name) {
            try {
                switch ($action) {
                    case 'delete':
                        Theme::delete($name);
                        break;
                }
            } catch (Throwable $e) {
                report($e);

                return $this->error(
                    [
                        'message' => $e->getMessage(),
                    ]
                );
            }
        }

        return $this->success(
            [
                'message' => trans('cms::app.successfully'),
                'redirect' => route('admin.themes'),
            ]
        );
    }

    protected function getDataUpdates(Collection $themes): ?object
    {
        if (!config('juzaweb.theme.enable_upload')) {
            return (object) [];
        }

        $key = sha1($themes->toJson());
        CacheGroup::add('theme_update_keys', $key);

        return Cache::remember(
            $key,
            3600,
            function () use ($themes) {
                try {
                    $response = $this->api->post(
                        'themes/versions-available',
                        [
                            'themes' => $themes->map(
                                function ($item) {
                                    return [
                                        'name' => $item['name'],
                                        'current_version' => $item['version'] ?? '1.0',
                                    ];
                                }
                            )->values()->toArray(),
                            'cms_version' => Version::getVersion(),
                        ]
                    );

                    if (empty($response->data)) {
                        return (object) [];
                    }

                    return $response->data;
                } catch (Exception $e) {
                    return (object) [];
                }
            }
        );
    }
}
