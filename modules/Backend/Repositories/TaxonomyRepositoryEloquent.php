<?php

namespace Juzaweb\Backend\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Juzaweb\Backend\Models\Taxonomy;
use Juzaweb\CMS\Repositories\BaseRepositoryEloquent;
use Juzaweb\CMS\Traits\Criterias\UseFilterCriteria;
use Juzaweb\CMS\Traits\Criterias\UseSearchCriteria;
use Juzaweb\CMS\Traits\Criterias\UseSortableCriteria;

/**
 * @property Taxonomy $model
 */
class TaxonomyRepositoryEloquent extends BaseRepositoryEloquent implements TaxonomyRepository
{
    use UseSortableCriteria, UseSearchCriteria, UseFilterCriteria, UseSortableCriteria;

    protected array $sortableFields = [
        'name',
        'total_post',
        'post_type',
        'taxonomy',
    ];
    
    protected array $searchableFields = [
        'name',
        'description',
    ];
    
    protected array $filterableFields = [
        'name',
        'total_post',
        'post_type',
        'taxonomy',
        'parent_id',
    ];
    
    public function findBySlug(string $slug): null|Taxonomy
    {
        return $this->model->newQuery()->where('slug', $slug)->firstOrFail();
    }
    
    public function frontendDetail(string $slug): ?Taxonomy
    {
        $this->applyCriteria();
        $this->applyScope();
        
        $result = $this->createFrontendBuilder()->where(['slug' => $slug])->firstOrFail();
    
        $this->resetModel();
        $this->resetScope();
    
        return $this->parserResult($result);
    }
    
    public function frontendListPaginate(int $limit): LengthAwarePaginator
    {
        $this->applyCriteria();
        $this->applyScope();
        
        $result = $this->createFrontendBuilder()->paginate($limit);
    
        $this->resetModel();
        $this->resetScope();
        
        return $this->parserResult($result);
    }
    
    public function createFrontendBuilder(): Builder
    {
        return $this->model->newQuery();
    }

    public function appendCustomFilter(Builder $builder, array $input): Builder
    {
        if ($root = Arr::get($input, 'root')) {
            $root = filter_var($root, \FILTER_VALIDATE_BOOL);

            $builder->when(
                $root,
                fn($q) => $q->where('parent_id', null),
                fn($q) => $q->where('parent_id', '!=', null),
            );
        }

        return $builder;
    }
    
    public function model(): string
    {
        return Taxonomy::class;
    }
}
