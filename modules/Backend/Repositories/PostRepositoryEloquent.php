<?php

namespace Juzaweb\Backend\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Juzaweb\Backend\Models\Post;
use Juzaweb\CMS\Models\User;
use Juzaweb\CMS\Repositories\BaseRepositoryEloquent;
use Juzaweb\CMS\Repositories\Criterias\SortCriteria;
use Juzaweb\CMS\Traits\Criterias\UseFilterCriteria;
use Juzaweb\CMS\Traits\Criterias\UseSearchCriteria;
use Juzaweb\CMS\Traits\Criterias\UseSortableCriteria;
use Juzaweb\Frontend\Criterias\FrontendPostCriteria;

/**
 * @property Post $model
 */
class PostRepositoryEloquent extends BaseRepositoryEloquent implements PostRepository
{
    use UseSearchCriteria, UseFilterCriteria, UseSortableCriteria;

    protected array $searchableFields = ['title', 'description'];

    protected array $filterableFields = [
        'status',
        'type',
        'locale',
        'created_by',
    ];

    protected array $sortableFields = [
        'id',
        'status',
        'title',
        'views',
        'created_at',
        'updated_at',
    ];

    protected array $sortableDefaults = ['id' => 'DESC'];

    public function boot(): void
    {
        parent::boot(); // TODO: Change the autogenerated stub
    }

    public function frontendFind(int|string $id, array $columns = ['*']): ?Post
    {
        $this->applyCriteria();
        $this->applyScope();

        $result = $this->createFrontendDetailBuilder()->where(['id' => $id])->first($columns);

        $this->resetModel();

        return $this->parserResult($result);
    }

    public function findBySlug(string $slug, bool $fail = true, array $columns = ['*'], bool $frontend = true): ?Post
    {
        if ($frontend) {
            return $this->frontendFindBySlug($slug, $fail, $columns);
        }

        $this->applyCriteria();
        $this->applyScope();

        if ($fail) {
            $result = $this->model->where(['slug' => $slug])->firstOrFail($columns);
        } else {
            $result = $this->model->where(['slug' => $slug])->first($columns);
        }

        $this->resetModel();

        return $this->parserResult($result);
    }

    public function frontendFindBySlug(string $slug, bool $fail = true, array $columns = ['*']): null|Post
    {
        $this->applyCriteria();
        $this->applyScope();

        if ($fail) {
            $result = $this->createFrontendDetailBuilder()->where(['slug' => $slug])->firstOrFail($columns);
        } else {
            $result = $this->createFrontendDetailBuilder()->where(['slug' => $slug])->first($columns);
        }

        $this->resetModel();

        return $this->parserResult($result);
    }

    public function findByUuid(string $uuid, array $columns = ['*'], bool $fail = true): null|Post
    {
        $this->applyCriteria();
        $this->applyScope();

        if ($fail) {
            $result = $this->model->where(['uuid' => $uuid])->firstOrFail();
        } else {
            $result = $this->model->where(['uuid' => $uuid])->first();
        }

        $this->resetModel();

        return $this->parserResult($result);
    }

    public function frontendListPaginate(int $limit, array $columns = ['*']): LengthAwarePaginator
    {
        $this->applyCriteria();
        $this->applyScope();

        $result = $this->createSelectFrontendBuilder()->paginate($limit, $columns);

        $this->resetModel();

        return $this->parserResult($result);
    }

    public function frontendListByTaxonomyPaginate(
        int $limit,
        int|array $taxonomy,
        ?int $page = null
    ): LengthAwarePaginator {
        $this->applyCriteria();
        $this->applyScope();

        $result = $this->createSelectFrontendBuilder()
            ->when(is_int($taxonomy), fn($q) => $q->whereTaxonomy($taxonomy), fn($q) => $q->whereTaxonomyIn($taxonomy))
            ->paginate($limit, [], 'page', $page);

        $this->resetModel();
        $this->resetScope();

        return $this->parserResult($result);
    }

    public function createSelectFrontendBuilder(): Builder
    {
        $builder = $this->model->newQuery()->with($this->withFrontendDefaults())
            ->cacheFor(3600)
            ->select(
                [
                    'id',
                    'uuid',
                    'title',
                    'description',
                    'thumbnail',
                    'slug',
                    'views',
                    'total_rating',
                    'total_comment',
                    'type',
                    'status',
                    'created_by',
                    'created_at',
                    'json_metas',
                    'json_taxonomies',
                ]
            )
            ->wherePublish();

        return apply_filters('post.selectFrontendBuilder', $builder);
    }

    public function createFrontendDetailBuilder(): Builder
    {
        $with = $this->withFrontendDefaults();
        /*$with['taxonomies'] = function ($q) {
            $q->cacheFor(3600);
            $q->limit(10);
        };*/

        $builder = $this->model->newQuery()->with($with)
            ->cacheFor(config('juzaweb.performance.query_cache.lifetime', 3600))
            ->whereIn('status', [Post::STATUS_PUBLISH, Post::STATUS_PRIVATE]);

        return apply_filters('post.createFrontendDetailBuilder', $builder);
    }

    public function withFrontendDefaults(): array
    {
        return [
            'createdBy' => function ($q) {
                $q->cacheFor(3600);
            },
        ];
    }

    public function frontend(?string $type): static
    {
        $this->pushCriteria(new FrontendPostCriteria($type));

        return $this;
    }

    public function getRelatedPosts(
        Post $post,
        string $taxonomy = 'categories',
        int $limit = 10,
        array $columns = ['*']
    ): Collection|array {
        $this->applyCriteria();
        $this->applyScope();

        $taxonomies = collect($post->json_taxonomies)
            ->when($taxonomy, fn ($q) => $q->where('taxonomy', $taxonomy))
            ->pluck('id')
            ->toArray();

        $results = $this->createSelectFrontendBuilder()
            ->whereTaxonomyIn($taxonomies)
            ->limit($limit)
            ->get($columns);

        $this->resetModel();

        return $this->parserResult($results);
    }

    public function getLikedPosts(User $user, int $limit = 10, array $columns = ['*']): LengthAwarePaginator|array
    {
        $this->applyCriteria();
        $this->applyScope();

        $results = $this->createSelectFrontendBuilder()->whereHas(
            'likes',
            fn ($q) => $q->where("{$q->getModel()->getTable()}.user_id", '=', $user->id)
        )->paginate($limit, $columns);

        $this->resetModel();

        return $this->parserResult($results);
    }

    public function getStatuses(string $type = 'posts'): array
    {
        $statuses = [
            Post::STATUS_PUBLISH => trans('cms::app.publish'),
            Post::STATUS_PRIVATE => trans('cms::app.private'),
            Post::STATUS_DRAFT => trans('cms::app.draft'),
            Post::STATUS_TRASH => trans('cms::app.trash'),
        ];

        return apply_filters($type.'.statuses', $statuses);
    }

    public function appendCustomFilter(Builder $builder, array $input): Builder
    {
        if ($taxonomy = Arr::get($input, 'taxonomy')) {
            $builder->whereTaxonomy($taxonomy);
        }

        return $builder;
    }

    public function model(): string
    {
        return Post::class;
    }
}
