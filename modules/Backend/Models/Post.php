<?php

namespace Juzaweb\Backend\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Juzaweb\CMS\Database\Factories\PostFactory;
use Juzaweb\CMS\Interfaces\Models\ExportSupport;
use Juzaweb\CMS\Models\Model;
use Juzaweb\CMS\Models\User;
use Juzaweb\CMS\Traits\Models\Exportable;
use Juzaweb\CMS\Traits\PostTypeModel;
use Juzaweb\CMS\Traits\QueryCache\QueryCacheable;
use Juzaweb\CMS\Traits\UseUUIDColumn;
use Juzaweb\Network\Traits\Networkable;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

/**
 * Juzaweb\Backend\Models\Post
 *
 * @property int $id
 * @property string $title
 * @property string|null $thumbnail
 * @property string $slug
 * @property string|null $description
 * @property string|null $content
 * @property string $status
 * @property int $views
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string $type
 * @property array|null $json_metas
 * @property array|null $json_taxonomies
 * @property float $rating
 * @property int $total_rating
 * @property int $total_comment
 * @property-read Collection|Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read User|null $createdBy
 * @property-read Collection|MenuItem[] $menuItems
 * @property-read int|null $menu_items_count
 * @property-read Collection|PostMeta[] $metas
 * @property-read int|null $metas_count
 * @property-read Collection|PostRating[] $ratings
 * @property-read int|null $post_ratings_count
 * @property-read Collection|PostView[] $postViews
 * @property-read int|null $post_views_count
 * @property-read Collection|Taxonomy[] $taxonomies
 * @property-read int|null $taxonomies_count
 * @property-read User|null $updatedBy
 * @method static PostFactory factory(...$parameters)
 * @method static Builder|Post newModelQuery()
 * @method static Builder|Post newQuery()
 * @method static Builder|Post query()
 * @method static Builder|Post whereContent($value)
 * @method static Builder|Post whereCreatedAt($value)
 * @method static Builder|Post whereCreatedBy($value)
 * @method static Builder|Post whereDescription($value)
 * @method static Builder|Post whereFilter($params = [])
 * @method static Builder|Post whereId($value)
 * @method static Builder|Post whereJsonMetas($value)
 * @method static Builder|Post whereJsonTaxonomies($value)
 * @method static Builder|Post whereMeta($key, $value)
 * @method static Builder|Post wherePublish()
 * @method static Builder|Post whereRating($value)
 * @method static Builder|Post whereSearch($params)
 * @method static Builder|Post whereSlug($value)
 * @method static Builder|Post whereStatus($value)
 * @method static Builder|Post whereTaxonomy($taxonomy)
 * @method static Builder|Post whereTaxonomyIn($taxonomies)
 * @method static Builder|Post whereThumbnail($value)
 * @method static Builder|Post whereTitle($value)
 * @method static Builder|Post whereTotalComment($value)
 * @method static Builder|Post whereTotalRating($value)
 * @method static Builder|Post whereType($value)
 * @method static Builder|Post whereUpdatedAt($value)
 * @method static Builder|Post whereUpdatedBy($value)
 * @method static Builder|Post whereViews($value)
 * @method static Builder|Post whereMetaIn($key, $values)
 * @property int|null $site_id
 * @property string|null $locale
 * @method static Builder|Post whereLocale($value)
 * @method static Builder|Post whereSiteId($value)
 * @property string|null $domain
 * @property string|null $url
 * @property-read Collection|Taxonomy[] $categories
 * @property-read int|null $categories_count
 * @property-read Collection|Taxonomy[] $tags
 * @property-read int|null $tags_count
 * @method static Builder|Post whereDomain($value)
 * @method static Builder|Post whereUrl($value)
 * @property-read Collection|PostLike[] $likes
 * @property-read int|null $likes_count
 * @property-read int|null $ratings_count
 * @property-read Collection|Resource[] $resources
 * @property-read int|null $resources_count
 * @property string|null $uuid
 * @method static Builder|Post whereUuid($value)
 * @mixin Eloquent
 */
class Post extends Model implements Feedable, ExportSupport
{
    protected static bool $flushCacheOnUpdate = true;

    use PostTypeModel, HasFactory, QueryCacheable, UseUUIDColumn, Networkable, Exportable;

    public string $cachePrefix = 'posts_';

    public const STATUS_PUBLISH = 'publish';
    public const STATUS_PRIVATE = 'private';
    public const STATUS_DRAFT = 'draft';
    public const STATUS_TRASH = 'trash';

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'content',
        'status',
        'views',
        'thumbnail',
        'slug',
        'type',
        'json_metas',
        'json_taxonomies',
        'rating',
        'total_rating',
        'locale',
    ];

    protected $searchFields = [
        'title',
    ];

    protected $casts = [
        'json_metas' => 'array',
        'json_taxonomies' => 'array',
    ];

    protected static function newFactory(): Factory
    {
        return PostFactory::new();
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class, 'post_id', 'id');
    }

    public function categories(): BelongsToMany
    {
        return $this->taxonomies()
            ->where('taxonomy', '=', 'categories');
    }

    public function tags(): BelongsToMany
    {
        return $this->taxonomies()->where('taxonomy', '=', 'tags');
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(
            MenuItem::class,
            'model_id',
            'id'
        )
            ->where(
                'model_class',
                '=',
                'Juzaweb\\Models\\Post'
            );
    }

    public function postViews(): HasMany
    {
        return $this->hasMany(PostView::class, 'post_id', 'id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(PostRating::class, 'post_id', 'id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class, 'post_id', 'id');
    }

    public function isPublish(): bool
    {
        return $this->status === self::STATUS_PUBLISH;
    }

    public function getTotalRating(): int
    {
        return $this->ratings()->count(['id']);
    }

    public function getStarRating(): float|int
    {
        $total = $this->ratings()->sum('star');
        $count = $this->getTotalRating();

        if ($count <= 0) {
            return 0;
        }

        return round($total * 5 / ($count * 5), 2);
    }

    public function toFeedItem(): FeedItem
    {
        $name = $this->getCreatedByName();
        $updated = $this->updated_at ?: now();
        if (empty($name)) {
            $name = 'Admin';
        }

        return FeedItem::create()
            ->id($this->id)
            ->title($this->title)
            ->summary(seo_string($this->content, 500) ?? '')
            ->updated($updated)
            ->link($this->getLink())
            ->authorName($name);
    }

    protected function getCacheBaseTags(): array
    {
        return [
            'posts',
        ];
    }
}
