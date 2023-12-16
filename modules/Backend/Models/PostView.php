<?php

namespace Juzaweb\Backend\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Juzaweb\CMS\Models\Model;
use Juzaweb\Network\Traits\Networkable;

/**
 * Juzaweb\Backend\Models\PostView
 *
 * @property int $id
 * @property int $post_id
 * @property int $views
 * @property string $day
 * @property-read Post $post
 * @method static Builder|PostView newModelQuery()
 * @method static Builder|PostView newQuery()
 * @method static Builder|PostView query()
 * @method static Builder|PostView whereDay($value)
 * @method static Builder|PostView whereId($value)
 * @method static Builder|PostView wherePostId($value)
 * @method static Builder|PostView whereSiteId($value)
 * @method static Builder|PostView whereViews($value)
 * @property int|null $site_id
 * @mixin Eloquent
 */
class PostView extends Model
{
    use Networkable;

    protected $table = 'post_views';

    protected $fillable = [
        'views',
        'day',
        'post_id',
    ];

    public $timestamps = false;

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }
}
