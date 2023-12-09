<?php

namespace Juzaweb\Backend\Http\Controllers\Backend\Plugin;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Juzaweb\Backend\Events\AfterPluginBulkAction;
use Juzaweb\Backend\Http\Requests\Plugin\BulkActionRequest;
use Juzaweb\CMS\Contracts\JuzawebApiContract;
use Juzaweb\CMS\Facades\CacheGroup;
use Juzaweb\CMS\Facades\Plugin;
use Juzaweb\CMS\Http\Controllers\BackendController;
use Juzaweb\CMS\Support\ArrayPagination;
use Juzaweb\CMS\Support\Plugin as SupportPlugin;
use Juzaweb\CMS\Version;
use Juzaweb\Network\Facades\Network;

class PluginController extends BackendController
{
    protected JuzawebApiContract $api;

    public function __construct(JuzawebApiContract $api)
    {
        $this->api = $api;
    }

    public function index(Request $request): View
    {
        if (!$request->user()->can('plugins.index')) {
            abort(403);
        }

        return view(
            'cms::backend.plugin.index',
            [
                'title' => trans('cms::app.plugins'),
            ]
        );
    }

    public function getDataTable(Request $request): JsonResponse
    {
        if (!$request->user()->can('plugins.index')) {
            abort(403);
        }

        $offset = $request->query('offset', 0);
        $limit = $request->query('limit', 20);
        $status = $request->query('status');

        $plugins = collect(apply_filters('admin.plugins.all', Plugin::all()));

        $plugins = $plugins->filter(
            fn (SupportPlugin $plugin) => apply_filters('admin.plugins.can_show', true, $plugin)
        );

        if (config('network.enable') && Network::isSubSite()) {
            $plugins = $plugins->filter(
                fn (SupportPlugin $plugin) => $plugin->isNetworkSupport()
            );
        }

        if ($keyword = $request->query('search')) {
            $plugins = $plugins->filter(
                fn (SupportPlugin $plugin) => mb_stripos($plugin->getDisplayName(), $keyword) !== false
            );
        }

        if ($status !== null) {
            $status = filter_var($status, FILTER_VALIDATE_BOOLEAN);

            $plugins = $plugins->filter(
                fn (SupportPlugin $plugin) => $status ? $plugin->isEnabled() : !$plugin->isEnabled()
            );
        }

        if ($networkable = $request->query('networkable')) {
            $networkable = filter_var($networkable, FILTER_VALIDATE_BOOLEAN);

            $plugins = $plugins->filter(
                fn (SupportPlugin $plugin) => $networkable ? $plugin->isNetworkSupport() : !$plugin->isNetworkSupport()
            );
        }

        $total = count($plugins);
        $page = (int) round(($offset + $limit) / $limit);
        $data = ArrayPagination::make($plugins);
        $data = $data->paginate($limit, $page);
        //$updates = $this->getDataUpdates($data->getCollection());

        $results = [];
        foreach ($data as $plugin) {
            /**
             * @var SupportPlugin $plugin
             */
            $results[] = [
                'id' => $plugin->get('name'),
                'name' => $plugin->getDisplayName(),
                'description' => $plugin->get('description'),
                'status' => $plugin->isEnabled() ? 'active' : 'inactive',
                'setting' => $plugin->getSettingUrl(),
                'version' => $plugin->getVersion(),
                'author' => $plugin->getAuthor(),
                //'update' => $updates->{$plugin->get('name')}->update ?? false,
                'update' => false,
                'networkable' => $plugin->isNetworkSupport(),
            ];
        }

        return response()->json(
            [
                'total' => $total,
                'rows' => $results,
            ]
        );
    }

    public function bulkActions(BulkActionRequest $request): JsonResponse
    {
        if (!$request->user()->can('plugins.edit')) {
            abort(403);
        }

        $action = $request->post('action');
        $ids = $request->post('ids');

        // if (in_array($action, ['update', 'install'])) {
        //     $query = [
        //         'plugins' => $ids,
        //         'action' => $action,
        //         'referren' => URL::previous(),
        //     ];
        //     $query = http_build_query($query);
        //
        //     return $this->success(
        //         [
        //             'window_redirect' => route('admin.update.process', ['plugin']).'?'.$query,
        //         ]
        //     );
        // }

        foreach ($ids as $plugin) {
            /**
             * @var SupportPlugin $module
             */
            $module = app('plugins')->find($plugin);
            $canActive = true;

            if (config('network.enable') && Network::isSubSite()) {
                $canActive = $module->isNetworkSupport();
            }

            $canActive = apply_filters('admin.plugins.can_active', $canActive, $module);

            try {
                switch ($action) {
                    case 'delete':
                        if (!config('juzaweb.plugin.enable_upload')) {
                            throw new \RuntimeException('Access deny.');
                        }

                        if ($module->isEnabled()) {
                            $module->disable();
                        }

                        $module->delete();
                        break;
                    case 'activate':
                        if (!$canActive) {
                            continue 2;
                        }

                        Plugin::enable($plugin);
                        break;
                    case 'deactivate':
                        if (!$canActive) {
                            continue 2;
                        }

                        Plugin::disable($plugin);
                        break;
                }
            } catch (\Throwable $e) {
                report($e);
                return $this->error(
                    [
                        'message' => $e->getMessage(),
                    ]
                );
            }
        }

        event(new AfterPluginBulkAction($action, $ids));

        return $this->success(
            [
                'message' => trans('cms::app.successfully'),
                'window_redirect' => route('admin.plugin'),
            ]
        );
    }

    protected function getDataUpdates(Collection $plugins): ?object
    {
        if (!config('juzaweb.plugin.enable_upload')) {
            return (object) [];
        }

        $key = sha1($plugins->toJson());
        CacheGroup::add('plugin_update_keys', $key);

        return Cache::remember(
            $key,
            3600,
            function () use ($plugins) {
                try {
                    $response = $this->api->post(
                        'plugins/versions-available',
                        [
                            'plugins' => $plugins->map(
                                function ($item) {
                                    return [
                                        'name' => $item->get('name'),
                                        'current_version' => $item->getVersion(),
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
                } catch (\Exception $e) {
                    return (object) [];
                }
            }
        );
    }
}
