<?php

namespace Juzaweb\Backend\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Juzaweb\CMS\Traits\QueryCache\QueryCacheable;
use Juzaweb\Network\Traits\Networkable;

/**
 * Juzaweb\Backend\Models\Notification
 *
 * @property int $id
 * @property string $type
 * @property string $url
 * @property string $subject
 * @property array $data
 * @property string|null $read_at
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Notification newModelQuery()
 * @method static Builder|Notification newQuery()
 * @method static Builder|Notification query()
 * @method static Builder|Notification whereCreatedAt($value)
 * @method static Builder|Notification whereData($value)
 * @method static Builder|Notification whereId($value)
 * @method static Builder|Notification whereNotifiableId($value)
 * @method static Builder|Notification whereNotifiableType($value)
 * @method static Builder|Notification whereReadAt($value)
 * @method static Builder|Notification whereType($value)
 * @method static Builder|Notification whereUpdatedAt($value)
 * @property int|null $site_id
 * @method static Builder|Notification whereSiteId($value)
 * @property-read Model|Eloquent $notifiable
 * @method static DatabaseNotificationCollection|static[] all($columns = ['*'])
 * @method static DatabaseNotificationCollection|static[] get($columns = ['*'])
 * @method static Builder|DatabaseNotification read()
 * @method static Builder|DatabaseNotification unread()
 * @mixin Eloquent
 */
class Notification extends DatabaseNotification
{
    use QueryCacheable, Networkable;

    public string $cachePrefix = 'notifications_';
    protected static bool $flushCacheOnUpdate = true;

    protected $fillable = [
        'id',
        'type',
        'data',
        'read_at',
        'notifiable_type',
        'notifiable_id',
    ];

    public $appends = [
        'subject',
        'url',
    ];

    public function getSubjectAttribute()
    {
        return Arr::get($this->data, 'subject');
    }

    public function getUrlAttribute()
    {
        return Arr::get($this->data, 'url');
    }

    protected function getCacheBaseTags(): array
    {
        return [
            'notifications',
        ];
    }
}
