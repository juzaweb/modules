<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Backend\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Juzaweb\CMS\Facades\HookAction;
use Juzaweb\CMS\Interfaces\Models\ExportSupport;
use Juzaweb\CMS\Models\Model;
use Juzaweb\CMS\Traits\Models\Exportable;
use Juzaweb\CMS\Traits\QueryCache\QueryCacheable;

/**
 * Juzaweb\Backend\Models\MenuItem
 *
 * @property int $id
 * @property int $menu_id
 * @property int|null $parent_id
 * @property string $name
 * @property string $model_class
 * @property int $model_id
 * @property string|null $link
 * @property string $type
 * @property string|null $icon
 * @property string $target
 * @property-read Menu $menu
 * @method static Builder|MenuItem newModelQuery()
 * @method static Builder|MenuItem newQuery()
 * @method static Builder|MenuItem query()
 * @method static Builder|MenuItem whereIcon($value)
 * @method static Builder|MenuItem whereId($value)
 * @method static Builder|MenuItem whereLink($value)
 * @method static Builder|MenuItem whereMenuId($value)
 * @method static Builder|MenuItem whereModelClass($value)
 * @method static Builder|MenuItem whereModelId($value)
 * @method static Builder|MenuItem whereName($value)
 * @method static Builder|MenuItem whereParentId($value)
 * @method static Builder|MenuItem whereTarget($value)
 * @method static Builder|MenuItem whereType($value)
 * @property string $group
 * @method static Builder|MenuItem whereGroup($value)
 * @method static Builder|MenuItem whereMenuKey($value)
 * @property string $box_key
 * @property string $label
 * @property int $num_order
 * @method static Builder|MenuItem whereBoxKey($value)
 * @method static Builder|MenuItem whereLabel($value)
 * @method static Builder|MenuItem whereNumOrder($value)
 * @property-read Taxonomy|null $post
 * @property-read Taxonomy|null $taxonomy
 * @property-read \Illuminate\Database\Eloquent\Collection|MenuItem[] $children
 * @property-read int|null $children_count
 * @property-read MenuItem|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|MenuItem[] $recursiveChildren
 * @property-read int|null $recursive_children_count
 * @mixin Eloquent
 */
class MenuItem extends Model implements ExportSupport
{
    use QueryCacheable, Exportable;

    public $timestamps = false;

    public string $cachePrefix = 'menu_items_';

    protected $table = 'menu_items';

    protected $fillable = [
        'label',
        'menu_id',
        'parent_id',
        'model_id',
        'link',
        'type',
        'icon',
        'target',
        'model_class',
        'model_id',
        'box_key',
        'num_order',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }

    public function taxonomy(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'model_id', 'id')->where(
            'model_class',
            '=',
            'Juzaweb\\Models\\Taxonomy'
        );
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'model_id', 'id')->where(
            'model_class',
            '=',
            'Juzaweb\\Models\\Post'
        );
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }

    public function recursiveChildren(): HasMany
    {
        return $this->children()->with(
            [
                'recursiveChildren' => fn ($q) => $q->cacheFor(
                    config('juzaweb.performance.query_cache.lifetime')
                )
            ]
        );
    }

    /**
     * @return Collection
     */
    public function menuBox(): Collection
    {
        return HookAction::getMenuBox($this->box_key);
    }

    public function isActive(): bool
    {
        return request()?->url() == $this->link;
    }

    protected function getCacheBaseTags(): array
    {
        return [
            'menu_items',
        ];
    }
}
