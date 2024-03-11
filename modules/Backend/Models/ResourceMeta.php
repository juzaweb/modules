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
use Juzaweb\CMS\Models\Model;

/**
 * Juzaweb\Backend\Models\ResourceMeta
 *
 * @property int $id
 * @property int $resource_id
 * @property string $meta_key
 * @property string|null $meta_value
 * @property-read Resource $resource
 * @method static Builder|ResourceMeta newModelQuery()
 * @method static Builder|ResourceMeta newQuery()
 * @method static Builder|ResourceMeta query()
 * @method static Builder|ResourceMeta whereId($value)
 * @method static Builder|ResourceMeta whereMetaKey($value)
 * @method static Builder|ResourceMeta whereMetaValue($value)
 * @method static Builder|ResourceMeta whereResourceId($value)
 * @mixin Eloquent
 */
class ResourceMeta extends Model
{
    public $timestamps = false;

    protected $table = 'resource_metas';
    protected $fillable = [
        'meta_key',
        'meta_value',
        'resource_id',
    ];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class, 'resource_id', 'id');
    }
}
