<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Juzaweb\Backend\Models\Post;
use Juzaweb\CMS\Facades\HookAction;

trait TaxonomyModel
{
    use UseSlug;
    use UseThumbnail;
    use ResourceModel;

    public static function bootTaxonomyModel(): void
    {
        static::saving(
            function ($model) {
                $model->setAttribute('level', $model->getLevel());
            }
        );
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function recursiveParents(): BelongsTo
    {
        return $this->parent()->with('recursiveParents');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function recursiveChildren(): HasMany
    {
        return $this->children()->with('recursiveChildren');
    }

    public function posts($postType = null): BelongsToMany
    {
        $postType = $postType ?: $this->getPostType('key');

        return $this->belongsToMany(Post::class, 'term_taxonomies', 'taxonomy_id', 'term_id')
            ->withPivot(['term_type'])
            ->wherePivot('term_type', '=', $postType);
    }

    /**
     * @param Builder $builder
     * @param array $params
     *
     * @return Builder
     */
    public function scopeWhereFilter($builder, $params = []): Builder
    {
        if ($taxonomy = Arr::get($params, 'taxonomy')) {
            $builder->where('taxonomy', '=', $taxonomy);
        }

        if ($postType = Arr::get($params, 'post_type')) {
            $builder->where('post_type', '=', $postType);
        }

        if ($keyword = Arr::get($params, 'keyword')) {
            $connection = config('database.default');
            $driver = config("database.connections.{$connection}.driver");
            $condition = $driver == 'pgsql' ? 'ilike' : 'like';
            
            $builder->where(
                function (Builder $q) use ($keyword, $condition) {
                    $q->where('name', $condition, '%'. $keyword .'%');
                    $q->orWhere('description', $condition, '%'. $keyword .'%');
                }
            );
        }

        return $builder;
    }

    public function getPostType($key = null)
    {
        $postType = HookAction::getPostTypes($this->post_type);
        if ($key) {
            return $postType->get($key);
        }

        return $postType;
    }

    public function getPermalink($key = null)
    {
        $permalink = HookAction::getPermalinks($this->taxonomy);

        if (empty($permalink)) {
            return false;
        }

        if (empty($key)) {
            return $permalink;
        }

        return $permalink->get($key);
    }

    public function getLink(): bool|string
    {
        $permalink = $this->getPermalink('base');
        if (empty($permalink)) {
            return false;
        }

        return home_url($permalink . '/' . $this->slug);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLevel(): int
    {
        $level = 0;
        recursive_level_model($level, $this);

        return $level;
    }
}
