<?php

namespace Juzaweb\CMS\Repositories\Criterias;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Juzaweb\CMS\Interfaces\Repositories\WithAppendFilter;
use Juzaweb\CMS\Repositories\Abstracts\Criteria;
use Juzaweb\CMS\Repositories\Contracts\CriteriaInterface;
use Juzaweb\CMS\Repositories\Contracts\RepositoryInterface;

class FilterCriteria extends Criteria implements CriteriaInterface
{
    public function __construct(protected ?array $queries = null)
    {
        if (is_null($this->queries)) {
            $this->queries = request()->all();
        }
    }

    /**
     * Apply criteria in query repository
     *
     * @param  Builder|Model  $model
     * @param  RepositoryInterface  $repository
     *
     * @return Builder|Model
     * @throws \Exception
     */
    public function apply($model, RepositoryInterface $repository): Builder|Model
    {
        if (!method_exists($repository, 'getFieldFilterable')) {
            return $model;
        }

        if ($model instanceof Model) {
            $model = $model->newQuery();
        }

        $fields = $repository->getFieldFilterable();
        $table = $model->getModel()->getTable();

        return $model->where(
            function ($query) use ($fields, $repository, $table) {
                foreach ($fields as $field => $condition) {
                    if (is_numeric($field)) {
                        $field = $condition;
                        $condition = "=";
                    }

                    if (!Arr::has($this->queries, $field)) {
                        continue;
                    }

                    if (is_array($condition)) {
                        $column = key($condition);
                        $condition = $condition[$column];
                        $value = $this->getValueRequest($field, $condition);
                    } else {
                        $condition = strtolower(trim($condition));
                        $value = $this->getValueRequest($field, $condition);
                        $column = $field;
                    }

                    if (is_null($value)) {
                        continue;
                    }

                    if (is_array($value) && $condition == '=') {
                        $condition = 'in';
                    }

                    switch ($condition) {
                        case 'in':
                            $query->whereIn("{$table}.{$column}", $value);
                            break;
                        case 'between':
                            $query->whereBetween("{$table}.{$column}", $value[0], $value[1]);
                            break;
                        default:
                            $query->where("{$table}.{$column}", $condition, $value);
                    }
                }

                if ($repository instanceof WithAppendFilter) {
                    $repository->appendCustomFilter($query, $this->queries);
                }
            }
        );
    }

    protected function getValueRequest(string $field, ?string $condition): ?string
    {
        $search = Arr::get($this->queries, $field);
        $value = null;

        if (!is_null($search) && !in_array($condition, ['in', 'between'])) {
            $value = ($condition == "like" || $condition == "ilike") ? "%{$search}%" : $search;
        }

        if ($condition == 'in') {
            $value = explode(',', $value);
            if ($field == $value[0] || trim($value[0]) === "") {
                $value = null;
            }
        }

        if ($condition == 'between') {
            $value = explode(',', $value);
            if (count($value) < 2) {
                $value = null;
            }
        }

        return $value;
    }
}
