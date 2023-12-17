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
 * Juzaweb\Backend\Models\PostMeta
 *
 * @property int $id
 * @property int $post_id
 * @property string $meta_key
 * @property string|null $meta_value
 * @property-read Post $post
 * @method static Builder|PostMeta newModelQuery()
 * @method static Builder|PostMeta newQuery()
 * @method static Builder|PostMeta query()
 * @method static Builder|PostMeta whereId($value)
 * @method static Builder|PostMeta whereMetaKey($value)
 * @method static Builder|PostMeta whereMetaValue($value)
 * @method static Builder|PostMeta wherePostId($value)
 * @mixin Eloquent
 */
class PostMeta extends Model
{
    public $timestamps = false;

    protected $table = 'post_metas';
    protected $fillable = [
        'meta_key',
        'meta_value',
        'post_id',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }
}
