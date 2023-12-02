<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Network\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Juzaweb\CMS\Models\User;
use Juzaweb\CMS\Models\UserMeta;

/**
 * Juzaweb\CMS\Models\UserMeta
 *
 * @property int $id
 * @property int $user_id
 * @property string $meta_key
 * @property string|null $meta_value
 * @property-read User $user
 * @method static Builder|UserMeta newModelQuery()
 * @method static Builder|UserMeta newQuery()
 * @method static Builder|UserMeta query()
 * @method static Builder|UserMeta whereId($value)
 * @method static Builder|UserMeta whereMetaKey($value)
 * @method static Builder|UserMeta whereMetaValue($value)
 * @method static Builder|UserMeta whereUserId($value)
 * @mixin Eloquent
 */
class NetworkUserMeta extends UserMeta
{
    protected $table = 'network_user_metas';
}
