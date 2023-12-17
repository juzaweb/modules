<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 *
 * Created by JUZAWEB.
 * Date: 8/12/2021
 * Time: 12:38 PM
 */

namespace Juzaweb\Backend\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Juzaweb\CMS\Facades\HookAction;
use Juzaweb\CMS\Interfaces\Models\ExportSupport;
use Juzaweb\CMS\Models\Model;
use Juzaweb\CMS\Traits\Models\Exportable;
use Juzaweb\CMS\Traits\QueryCache\QueryCacheable;
use Juzaweb\CMS\Traits\UseUUIDColumn;
use Juzaweb\Network\Traits\Networkable;

/**
 * Juzaweb\Backend\Models\Menu
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|MenuItem[] $items
 * @property-read int|null $items_count
 * @method static Builder|Menu newModelQuery()
 * @method static Builder|Menu newQuery()
 * @method static Builder|Menu query()
 * @method static Builder|Menu whereCreatedAt($value)
 * @method static Builder|Menu whereDescription($value)
 * @method static Builder|Menu whereId($value)
 * @method static Builder|Menu whereName($value)
 * @method static Builder|Menu whereUpdatedAt($value)
 * @property int|null $site_id
 * @method static Builder|Menu whereSiteId($value)
 * @property string|null $uuid
 * @method static Builder|Menu whereUuid($value)
 * @mixin Eloquent
 */
class Menu extends Model implements ExportSupport
{
    use QueryCacheable, UseUUIDColumn, Networkable, Exportable;

    public string $cachePrefix = 'menus_';

    protected $table = 'menus';

    protected $fillable = [
        'name',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_id', 'id');
    }

    public function syncItems(array $items, ?int $parentId = null): array
    {
        $order = 1;
        $result = [];
        foreach ($items as $item) {
            $result = array_merge(
                $result,
                $this->saveItem($item, $order, $parentId)
            );
        }

        $this->items()
            ->whereNotIn('id', $result)
            ->delete();

        return $result;
    }

    public function saveItem(array $item, &$order, ?int $parentId = null): array
    {
        $result = [];
        $menuBox = HookAction::getMenuBox($item['box_key']);
        if (empty($menuBox)) {
            return $result;
        }

        $menuBox = $menuBox->get('menu_box');
        $data = $menuBox->getData($item);
        $data['parent_id'] = $parentId;
        $data['menu_id'] = $this->id;
        $data['num_order'] = $order;
        $data['box_key'] = $item['box_key'];
        $data['target'] = $item['target'] ?? '_self';

        $model = $this->items()->updateOrCreate(
            [
                'id' => $item['id'] ?? null,
            ],
            $data
        );

        $order++;
        $result[$model->id] = $model->id;

        if ($children = Arr::get($item, 'children')) {
            foreach ($children as $child) {
                $result = array_merge(
                    $result,
                    $this->saveItem($child, $order, $model->id)
                );
            }
        }

        return $result;
    }

    public function getLocation(): ?string
    {
        $locations = get_theme_config('nav_location');
        return array_search($this->id, $locations) ?: null;
    }

    protected function getCacheBaseTags(): array
    {
        return [
            'menus',
        ];
    }
}
