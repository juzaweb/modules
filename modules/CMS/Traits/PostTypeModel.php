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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Juzaweb\Backend\Http\Resources\TaxonomyResource;
use Juzaweb\Backend\Models\Comment;
use Juzaweb\Backend\Models\Post;
use Juzaweb\Backend\Models\PostMeta;
use Juzaweb\Backend\Models\Taxonomy;
use Juzaweb\Backend\Support\PostContentParser;
use Juzaweb\CMS\Facades\HookAction;
use Juzaweb\CMS\Facades\ShortCode;
use Juzaweb\CMS\Traits\Models\UseMeta;

/**
 * @method Builder wherePublish()
 * @method Builder whereTaxonomy($taxonomy)
 * @method Builder whereTaxonomyIn($taxonomies)
 * @method Builder whereFilter(array $params)
 * @method Builder whereMeta($key, $val)
 */
trait PostTypeModel
{
    use ResourceModel;
    use UseSlug;
    use UseThumbnail;
    use UseChangeBy;
    use UseDescription;
    use UseMeta;

    public static function selectFrontendBuilder(): Builder
    {
        $builder = static::with(
            [
                'createdBy' => function ($q) {
                    $q->cacheFor(3600);
                }
            ]
        )
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
            )->wherePublish();

        return apply_filters('post.selectFrontendBuilder', $builder);
    }

    public static function createFrontendDetailBuilder(): Builder
    {
        $builder = static::with(
            [
                'createdBy' => function ($q) {
                    $q->cacheFor(3600);
                },
            ]
        )
            ->cacheFor(3600)
            ->whereIn('status', [Post::STATUS_PUBLISH, Post::STATUS_PRIVATE]);

        return apply_filters('post.createFrontendDetailBuilder', $builder);
    }

    /**
     * Create Builder for frontend
     *
     * @return Builder
     */
    public static function createFrontendBuilder(): Builder
    {
        $builder = static::with(
            [
                'createdBy' => function ($q) {
                    $q->cacheFor(3600);
                },
            ]
        )
            ->cacheFor(3600)
            ->wherePublish();

        return apply_filters('post.createFrontendBuilder', $builder);
    }

    public static function getStatuses($type = 'posts'): array
    {
        $statuses = [
            'publish' => trans('cms::app.publish'),
            'private' => trans('cms::app.private'),
            'draft' => trans('cms::app.draft'),
            'trash' => trans('cms::app.trash'),
        ];

        return apply_filters($type . '.statuses', $statuses);
    }

    public function attributeLabels(): array
    {
        return apply_filters(
            "{$this->type}.attribute_labels",
            [
                'title' => trans('cms::app.title'),
                'content' => trans('cms::app.content'),
                'status' => trans('cms::app.status'),
                'slug' => trans('cms::app.slug'),
                'thumbnail' => trans('cms::app.thumbnail'),
                'views' => trans('cms::app.views'),
            ]
        );
    }

    public function taxonomies(): BelongsToMany
    {
        return $this->belongsToMany(
            Taxonomy::class,
            'term_taxonomies',
            'term_id',
            'taxonomy_id'
        )
            ->withPivot(['term_type']);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'object_id', 'id');
    }

    public function metas(): HasMany
    {
        return $this->hasMany(PostMeta::class, 'post_id', 'id');
    }

    /**
     * @param  Builder  $builder
     * @param  array  $params
     *
     * @return Builder
     */
    public function scopeWhereFilter(Builder $builder, array $params = []): Builder
    {
        if ($keyword = Arr::get($params, 'q')) {
            $keyword = trim($keyword);
            $connection = config('database.default');
            $driver = config("database.connections.{$connection}.driver");
            $condition = $driver == 'pgsql' ? 'ilike' : 'like';

            $builder->where(
                function (Builder $q) use ($keyword, $condition) {
                    $q->where('title', $condition, '%'.$keyword.'%');
                    $q->orWhere('description', $condition, '%'.$keyword.'%');
                }
            );
        }

        if ($locale = Arr::get($params, 'locale')) {
            $builder->where('locale', '=', $locale);
        }

        if ($status = Arr::get($params, 'status')) {
            $builder->where('status', '=', $status);
        }

        if ($type = Arr::get($params, 'type')) {
            $builder->where('type', '=', $type);
            $taxonomies = HookAction::getTaxonomies($type);

            foreach ($taxonomies as $key => $taxonomy) {
                $ids = Arr::get($params, $key, []);
                if (! is_array($ids)) {
                    $ids = [$ids];
                }

                $ids = array_filter(
                    $ids,
                    function ($item) {
                        return !empty($item);
                    }
                );

                if ($ids) {
                    $builder->whereTaxonomyIn($ids);
                }
            }
        }

        if ($metas = Arr::get($params, 'meta')) {
            foreach ($metas as $key => $val) {
                if (is_null($metas[$key])) {
                    continue;
                }

                $builder->whereMeta($key, $val);
            }
        }

        if ($sort = Arr::get($params, 'sort')) {
            switch ($sort) {
                case 'latest':
                    $builder->orderBy('id', 'DESC');
                    break;
                case 'top_views':
                    $builder->orderBy('views', 'DESC');
                    break;
                case 'new_update':
                    $builder->orderBy('updated_at', 'DESC');
                    break;
            }
        }

        return $builder;
    }



    /**
     * @param Builder $builder
     * @param array $params
     *
     * @return Builder
     */
    public function scopeWhereSearch(Builder $builder, array $params): Builder
    {
        $builder->whereFilter($params);

        return apply_filters(
            'frontend.search_query',
            $builder,
            $params
        );
    }

    /**
     * Get taxonomies by taxonomy
     *
     * @param string|null $taxonomy
     * @param int|null $limit
     * @param bool $tree
     * @return Collection
     */
    public function getTaxonomies(
        string $taxonomy = null,
        ?int $limit = null,
        bool $tree = false
    ): Collection {
        $taxonomies = $this->taxonomies;

        if ($taxonomy) {
            $taxonomies = $taxonomies->where('taxonomy', $taxonomy);
        }

        if ($tree) {
            $taxonomies = $taxonomies->orderBy('level', 'ASC');
        }

        if ($limit) {
            $taxonomies = $taxonomies->take($limit);
        }

        return $taxonomies;
    }

    public function getRelatedPosts(int $limit = 5, string $taxonomy = null): Collection
    {
        $ids = $this->getTaxonomies($taxonomy)->pluck('id')->toArray();

        return self::whereHas(
            'taxonomies',
            function (Builder $q) use ($ids) {
                $q->whereIn("{$q->getModel()->getTable()}.id", $ids);
            }
        )
            ->where('id', '!=', $this->id)
            ->take($limit)
            ->get();
    }

    /**
     * @param array $attributes
     * @throws \Exception
     */
    public function syncTaxonomies(array $attributes): void
    {
        if (empty($this->type)) {
            throw new \Exception('Cannot find Type in post.');
        }

        $taxonomies = HookAction::getTaxonomies($this->type);
        foreach ($taxonomies as $taxonomy) {
            $this->syncTaxonomy(
                $taxonomy->get('taxonomy'),
                $attributes,
                $this->type
            );
        }

        $this->update(
            [
                'json_taxonomies' => TaxonomyResource::collection(
                    $this->taxonomies()->get()
                )->toArray(request())
            ]
        );
    }

    public function syncTaxonomy(
        string $taxonomy,
        array $attributes,
        string $postType = null
    ): bool {
        $postType = $postType ?: $this->type;
        $data = (array) Arr::get($attributes, $taxonomy, []);

        $detachIds = $this->taxonomies()
            ->where('taxonomy', '=', $taxonomy)
            ->whereNotIn('id', $data)
            ->pluck('id')
            ->toArray();

        $this->taxonomies()->detach($detachIds);

        $this->taxonomies()
            ->syncWithoutDetaching(
                combine_pivot(
                    $data,
                    [
                        'term_type' => $postType,
                    ]
                )
            );

        $taxonomies = Taxonomy::where('taxonomy', '=', $taxonomy)
            ->whereIn('id', array_merge($detachIds, $data))
            ->get();

        foreach ($taxonomies as $tax) {
            $tax->update(
                [
                    'total_post' => $tax->posts()->count(),
                ]
            );
        }

        return true;
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     **/
    public function scopeWherePublish(Builder $builder): Builder
    {
        $builder->where('status', '=', 'publish');

        return $builder;
    }

    /**
     * @param Builder $builder
     * @param int $taxonomy
     *
     * @return Builder
     **/
    public function scopeWhereTaxonomy(Builder $builder, int $taxonomy): Builder
    {
        $builder->whereHas(
            'taxonomies',
            function (Builder $q) use ($taxonomy) {
                $q->where($q->getModel()->getTable() . '.id', $taxonomy);
            }
        );

        return $builder;
    }

    /**
     * @param Builder $builder
     * @param array $taxonomies
     *
     * @return Builder
     */
    public function scopeWhereTaxonomyIn(Builder $builder, array $taxonomies): Builder
    {
        $builder->whereHas(
            'taxonomies',
            function (Builder $q) use ($taxonomies) {
                $q->whereIn(
                    $q->getModel()->getTable() . '.id',
                    $taxonomies
                );
            }
        );

        return $builder;
    }

    public function getPostType($key = null)
    {
        if ($key == 'key') {
            return $this->type;
        }

        $postType = HookAction::getPostTypes()
            ->where('key', '=', $this->type)
            ->first();

        if (empty($key)) {
            return $postType;
        }

        return $postType->get($key);
    }

    public function getPostTypeMetaKeys(): array
    {
        return array_keys($this->getPostType('metas'));
    }

    public function getPermalink($key = null)
    {
        $permalink = HookAction::getPermalinks($this->type);

        if (empty($permalink)) {
            return false;
        }

        if (empty($key)) {
            return $permalink;
        }

        return $permalink->get($key);
    }

    public function getTitle($words = null): string
    {
        if ($words > 0) {
            return apply_filters(
                $this->type . '.get_title',
                Str::words(
                    $this->{$this->getFieldName()},
                    $words
                ),
                $words
            );
        }

        return apply_filters(
            $this->type . '.get_title',
            $this->{$this->getFieldName()},
            $words
        );
    }

    public function getContent(): string
    {
        $content = $this->content ?? '';
        if ($content) {
            $content = Cache::store('file')->remember(
                "post_type.get_content.{$this->id}",
                3600,
                function () {
                    return PostContentParser::make($this)->parse();
                }
            );
        }

        if ($this->type == 'pages') {
            $content = ShortCode::compile($content);
        }

        return apply_filters(
            $this->type . '.get_content',
            $content
        );
    }

    public function getLink(bool $absolute = true): bool|string
    {
        if ($this->type == 'pages') {
            return home_url(route('post', [$this->slug], false), $absolute);
        }

        $permalink = $this->getPermalink('base');
        if (empty($permalink)) {
            return false;
        }

        return home_url(route('post', ["{$permalink}/{$this->slug}"], false), $absolute);
    }

    public function getThumbnail(string|bool $thumb = true): string
    {
        if (empty($this->thumbnail)) {
            $thumbnailDefault = get_config('thumbnail_defaults', [])[$this->type] ?? null;
            if ($thumbnailDefault) {
                return upload_url($thumbnailDefault);
            }
        }

        if ($thumb && is_bool($thumb) && ($size = get_thumbnail_size($this->type))) {
            return upload_url($this->thumbnail, null, "{$size['width']}x{$size['height']}");
        }

        if ($thumb && is_string($thumb)) {
            return upload_url($this->thumbnail, null, $thumb);
        }

        return upload_url($this->thumbnail);
    }

    public function getUpdatedDate($format = JW_DATE_TIME): string
    {
        return jw_date_format($this->updated_at, $format);
    }

    public function getCreatedDate($format = JW_DATE_TIME): string
    {
        return jw_date_format($this->updated_at, $format);
    }

    public function getCreatedByName(): string
    {
        if ($this->createdBy) {
            return $this->createdBy->name;
        }

        return 'Admin';
    }

    public function getCreatedByAvatar(): string
    {
        if ($this->createdBy) {
            return $this->createdBy->getAvatar();
        }

        return asset('jw-styles/juzaweb/images/avatar.png');
    }

    public function getViews(): int|string
    {
        if ($this->views < 1000) {
            return $this->views;
        }

        return round($this->views / 1000, 1) . 'K';
    }

    public function getTotalComments(): int
    {
        return $this->comments()->whereApproved()->count();
    }
}
