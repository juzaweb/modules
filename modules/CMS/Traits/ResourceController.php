<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://github.com/juzaweb/cms
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Traits;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Inertia\Response;
use Juzaweb\Backend\Support\ElementBuilders\IndexResourceBuilder;
use Juzaweb\CMS\Abstracts\DataTable;
use Juzaweb\CMS\Interfaces\ElementBuilders\ElementBuilder as ElementBuilderInterface;

/**
 * @method void getBreadcrumbPrefix(...$params)
 */
trait ResourceController
{
    public function index(...$params): View|Response
    {
        $this->checkPermission(
            'index',
            $this->getModel(...$params),
            ...$params
        );

        if (method_exists($this, 'getBreadcrumbPrefix')) {
            $this->getBreadcrumbPrefix(...$params);
        }

        return $this->renderView('index', $this->getDataForIndex(...$params));
    }

    public function update(Request $request, ...$params): JsonResponse|RedirectResponse
    {
        $validator = $this->validator($request->all(), ...$params);
        if (is_array($validator)) {
            $validator = Validator::make($request->all(), $validator);
        }

        $validator->validate();
        $data = $this->parseDataForSave($request->all(), ...$params);

        $model = $this->getDetailModel($this->makeModel(...$params), ...$params);
        $this->checkPermission('edit', $model, ...$params);

        DB::beginTransaction();
        try {
            if (method_exists($this, 'beforeUpdate')) {
                $this->beforeUpdate($request, $model, ...$params);
            }

            $slug = $request->input('slug');
            if ($slug && method_exists($model, 'generateSlug')) {
                $data['slug'] = $model->generateSlug($slug);
            }

            $model->fill($data);
            if (method_exists($this, 'beforeSave')) {
                $this->beforeSave($data, $model, ...$params);
            }
            $model->save();

            if (method_exists($this, 'afterUpdate')) {
                $this->afterUpdate($request, $model, ...$params);
            }

            if (method_exists($this, 'afterSave')) {
                $this->afterSave($data, $model, ...$params);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        if (method_exists($this, 'updateSuccess')) {
            $this->updateSuccess($request, $model, ...$params);
        }

        if (method_exists($this, 'saveSuccess')) {
            $this->saveSuccess($request, $model, ...$params);
        }

        return $this->updateSuccessResponse(
            $model,
            $request,
            ...$params
        );
    }

    public function create(...$params): View|Response
    {
        $this->checkPermission('create', $this->getModel(...$params), ...$params);

        $indexRoute = str_replace(
            '.create',
            '.index',
            Route::currentRouteName()
        );

        if (method_exists($this, 'getBreadcrumbPrefix')) {
            $this->getBreadcrumbPrefix(...$params);
        }

        $this->addBreadcrumb(
            [
                'title' => $this->getTitle(...$params),
                'url' => route($indexRoute, $params),
            ]
        );

        $model = $this->makeModel(...$params);

        return $this->view(
            "{$this->viewPrefix}.form",
            array_merge(
                [
                    'title' => trans('cms::app.add_new'),
                    'linkIndex' => action([static::class, 'index'], $params)
                ],
                $this->getDataForForm($model, ...$params)
            )
        );
    }

    public function edit(...$params): View|Response
    {
        $indexRoute = str_replace(
            '.edit',
            '.index',
            Route::currentRouteName()
        );

        $indexParams = $params;
        unset($indexParams[$this->getPathIdIndex($indexParams)]);
        $indexParams = collect($indexParams)->values()->toArray();

        if (method_exists($this, 'getBreadcrumbPrefix')) {
            $this->getBreadcrumbPrefix(...$params);
        }

        $this->addBreadcrumb(
            [
                'title' => $this->getTitle(...$params),
                'url' => route($indexRoute, $indexParams),
            ]
        );

        $model = $this->getDetailModel($this->makeModel(...$indexParams), ...$params);
        $this->checkPermission('edit', $model, ...$params);

        return $this->view(
            $this->viewPrefix.'.form',
            array_merge(
                [
                    'title' => $model->{$model->getFieldName()},
                    'linkIndex' => action([static::class, 'index'], $indexParams)
                ],
                $this->getDataForForm($model, ...$params)
            )
        );
    }

    public function store(Request $request, ...$params): JsonResponse|RedirectResponse
    {
        $this->checkPermission('create', $this->getModel(...$params), ...$params);

        $validator = $this->validator($request->all(), ...$params);
        if (is_array($validator)) {
            $validator = Validator::make($request->all(), $validator);
        }

        $validator->validate();
        $data = $this->parseDataForSave($request->all(), ...$params);

        $model = DB::transaction(
            function () use ($data, $request, $params) {
                $this->beforeStore($request, ...$params);
                $model = $this->makeModel(...$params);
                $slug = $request->input('slug');

                if ($slug && method_exists($model, 'generateSlug')) {
                    $data['slug'] = $model->generateSlug($slug);
                }

                if (method_exists($this, 'beforeSave')) {
                    $this->beforeSave($data, $model, ...$params);
                }

                $model->fill($data);

                $model->save();

                $this->afterStore($request, $model, ...$params);

                if (method_exists($this, 'afterSave')) {
                    $this->afterSave($data, $model, ...$params);
                }

                return $model;
            }
        );

        if (method_exists($this, 'storeSuccess')) {
            $this->storeSuccess($request, $model, ...$params);
        }

        if (method_exists($this, 'saveSuccess')) {
            $this->saveSuccess($request, $model, ...$params);
        }

        return $this->storeSuccessResponse(
            $model,
            $request,
            ...$params
        );
    }

    public function datatable(Request $request, ...$params): JsonResponse
    {
        $this->checkPermission(
            'index',
            $this->getModel(...$params),
            ...$params
        );

        $table = $this->getDataTable(...$params);
        $table->setCurrentUrl(action([static::class, 'index'], $params, false));
        list($count, $rows) = $table->getData($request);

        $results = [];
        $columns = $table->columns();

        foreach ($rows as $index => $row) {
            $columns['id'] = $row->id;
            foreach ($columns as $col => $column) {
                if (!empty($column['formatter'])) {
                    $formatter = $column['formatter'](
                        $row->{$col} ?? null,
                        $row,
                        $index
                    );

                    if ($formatter instanceof Renderable) {
                        $formatter = $formatter->render();
                    }

                    $results[$index][$col] = $formatter;
                } else {
                    $results[$index][$col] = $row->{$col};
                }

                if (!empty($column['detailFormater'])) {
                    $results[$index]['detailFormater'] = $column['detailFormater'](
                        $index,
                        $row
                    );
                }
            }
        }

        return response()->json(
            [
                'total' => $count,
                'rows' => $results,
            ]
        );
    }

    public function bulkActions(Request $request, ...$params): JsonResponse|RedirectResponse
    {
        $request->validate(
            [
                'ids' => 'required|array',
                'action' => 'required',
            ]
        );

        $action = $request->post('action');
        $ids = $request->post('ids');

        $table = $this->getDataTable(...$params);
        $results = [];

        foreach ($ids as $id) {
            $model = $this->makeModel(...$params)->find($id);
            $permission = $action != 'delete' ? 'edit' : 'delete';

            if (!$this->hasPermission($permission, $model, ...$params)) {
                continue;
            }

            $results[] = $id;
        }

        $table->bulkActions($action, $results);

        return $this->success(
            [
                'message' => trans('cms::app.successfully'),
            ]
        );
    }

    public function getDataForSelect(Request $request, ...$params): JsonResponse
    {
        $queries = $request->query();
        $exceptIds = $request->get('except_ids');
        $model = $this->makeModel(...$params);
        $limit = $request->get('limit', 10);

        if ($limit > 100) {
            $limit = 100;
        }

        $query = $model::query();
        $query->select(
            [
                'id',
                $model->getFieldName().' AS text',
            ]
        );

        $query->whereFilter($queries);

        if ($exceptIds) {
            $query->whereNotIn('id', $exceptIds);
        }

        $paginate = $query->paginate($limit);
        $data['results'] = $query->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        return response()->json($data);
    }

    protected function checkPermission($ability, $arguments = [], ...$params): void
    {
        $this->authorize($ability, $arguments);
    }

    /**
     * Get model resource
     *
     * @param  array  $params
     * @return string // namespace model
     */
    abstract protected function getModel(...$params): string;

    /**
     * @throws Exception
     */
    protected function getDataForIndex(...$params): array
    {
        $dataTable = $this->getDataTable(...$params);
        $dataTable->setDataUrl(action([static::class, 'datatable'], $params));
        $dataTable->setActionUrl(action([static::class, 'bulkActions'], $params));
        $dataTable->setCurrentUrl(action([static::class, 'index'], $params, false));

        $canCreate = $this->hasPermission(
            'create',
            $this->getModel(...$params),
            ...$params
        );

        $data = [
            'title' => $this->getTitle(...$params),
            'dataTable' => $dataTable,
            'canCreate' => $canCreate,
            'linkCreate' => action([static::class, 'create'], $params),
        ];

        if (method_exists($this, 'getSetting')) {
            $data['setting'] = $this->getSetting(...$params);
        }

        return $data;
    }

    /**
     * Get data table resource
     *
     * @param  mixed  ...$params
     * @return DataTable
     */
    abstract protected function getDataTable(...$params): DataTable;

    protected function hasPermission($ability, $arguments = [], ...$params): bool
    {
        return Gate::inspect($ability, $arguments)->allowed();
    }

    /**
     * Get title resource
     *
     * @param  array  $params
     * @return string
     */
    abstract protected function getTitle(...$params): string;

    /**
     * @param $params
     * @return Model
     */
    protected function makeModel(...$params): Model
    {
        return app($this->getModel(...$params));
    }

    /**
     * Get data for form
     *
     * @param  Model  $model
     * @param  mixed  ...$params
     * @return array
     * @throws Exception
     */
    protected function getDataForForm(Model $model, ...$params): array
    {
        $data = [
            'model' => $model
        ];

        if (method_exists($this, 'getSetting')) {
            $data['setting'] = $this->getSetting(...$params);
        }

        return $data;
    }

    protected function getPathIdIndex($params): int
    {
        return count($params) - 1;
    }

    protected function getDetailModel(Model $model, ...$params): Model
    {
        return $model
            ->where($this->editKey ?? 'id', $this->getPathId($params))
            ->firstOrFail();
    }

    protected function getPathId($params)
    {
        return $params[$this->getPathIdIndex($params)];
    }

    /**
     * Validator for store and update
     *
     * @param  array  $attributes
     * @param  mixed  ...$params
     * @return ValidatorContract|array
     */
    abstract protected function validator(array $attributes, ...$params): ValidatorContract|array;

    protected function parseDataForSave(array $attributes, ...$params): array
    {
        return $attributes;
    }

    protected function beforeStore(Request $request, ...$params)
    {
        //
    }

    protected function afterStore(Request $request, $model, ...$params)
    {
        //
    }

    protected function storeSuccessResponse(Model $model, Request $request, ...$params): JsonResponse|RedirectResponse
    {
        $indexRoute = str_replace(
            '.store',
            '.index',
            Route::currentRouteName()
        );

        return $this->success(
            [
                'message' => trans('cms::app.created_successfully'),
                'redirect' => route($indexRoute, $params),
            ]
        );
    }

    protected function updateSuccessResponse($model, $request, ...$params): JsonResponse|RedirectResponse
    {
        return $this->success(
            [
                'message' => trans('cms::app.updated_successfully'),
            ]
        );
    }

    protected function isUpdateRoute(): bool
    {
        return Route::getCurrentRoute()?->getName() == 'admin.resource.update';
    }

    protected function getElementBuilder($view, array $params): ElementBuilderInterface
    {
        if ($view === 'index') {
            return new IndexResourceBuilder($params);
        }

        return [];
    }

    protected function renderView(string $view, array $params): View|Response
    {
        if (isset($this->viewPrefix)) {
            return $this->view("{$this->viewPrefix}.{$view}", $params);
        }

        $params['builder'] = $this->getElementBuilder($view, $params);

        return $this->view(
            'cms::backend.builder',
            $params
        );
    }
}
